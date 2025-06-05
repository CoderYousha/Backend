<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicRequest;
use App\Models\Clinic;
use App\Models\DoctorClinic;
use App\Models\User;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    //Create Clinic Function
    public function createClinic(ClinicRequest $clinicRequest)
    {
        Clinic::create([
            'clinic_name' => $clinicRequest->clinic_name,
            'floor_number' => $clinicRequest->floor_number,
        ]);

        return success(null, 'clinic created successfully', 201);
    }

    //Edit Clinic Function
    public function editClinic(Clinic $clinic, ClinicRequest $clinicRequest)
    {
        $clinic->update([
            'clinic_name' => $clinicRequest->clinic_name,
            'floor_number' => $clinicRequest->floor_number,
        ]);

        return success(null, 'clinic updated successfully');
    }

    //Delete Clinic Function
    public function deleteClinic(Clinic $clinic)
    {
        $clinic->delete();

        return success(null, 'this clinic deleted successfully');
    }

    //Add Doctor To Clinic Function
    public function addDoctorToClinic(Clinic $clinic, User $user)
    {
        $doctor_clinic = DoctorClinic::where('clinic_id', $clinic->id)->where('doctor_id', $user->id)->first();
        if ($doctor_clinic) {
            return error('some thing went wrong', 'this doctor already added to this clinic', 422);
        }

        DoctorClinic::create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $user->id,
        ]);

        return success(null, 'doctor ' . $user->first_name . ' ' . $user->last_name . ' added to ' . $clinic->clinic_name, 201);
    }

    //Remove Doctor From Clinic Function
    public function removeDoctorFromClinic(Clinic $clinic, User $user)
    {
        $doctor_clinic = DoctorClinic::where('clinic_id', $clinic->id)->where('doctor_id', $user->id)->first();
        if ($doctor_clinic) {
            $doctor_clinic->delete();

            return success(null, 'this doctor removed from this clinic successfully');
        }
    }

    //Get Clinics Function
    public function getClinics(Request $request)
    {
        $clinics = Clinic::where('clinic_name', 'LIKE', '%' . $request->search . '%')->get();
        return success($clinics, null);
    }

    //Get Clinic Information Function
    public function getClinicInformation(Clinic $clinic, Request $request)
    {
        $search = $request->search;
        $clinic = $clinic->with(['doctors' => function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }])->find($clinic->id);
        return success($clinic, null);
    }
}

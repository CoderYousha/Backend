<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    //Create Patient Function
    public function createPatient(PatientRequest $patientRequest)
    {
        if ($patientRequest->email) {
            $patientRequest->validate([
                'email' => 'email|unique:users,email',
            ]);
        }
        if ($patientRequest->username) {
            $patientRequest->validate([
                'username' => 'unique:users,username',
            ]);
        }
        if ($patientRequest->username || $patientRequest->email) {
            $patientRequest->validate([
                'password' => 'required|min:8',
            ]);
        }

        User::create([
            'name' => $patientRequest->name,
            'username' => $patientRequest->username,
            'email' => $patientRequest->email,
            'phone_number' => $patientRequest->phone_number,
            'birth_date' => $patientRequest->birth_date,
            'address' => $patientRequest->address,
            'password' => $patientRequest->password ? Hash::make($patientRequest->password) : null,
            'account_type' => 'patient',
        ]);

        return success(null, 'patient account created successfully', 201);
    }

    //Edit Patient Function
    public function editPatient(User $user, UpdatePatientRequest $updatePatientRequest)
    {
        if ($updatePatientRequest->email) {
            $updatePatientRequest->validate([
                'email' => 'email|unique:users,email,' . $user->id,
            ]);
        }
        if ($updatePatientRequest->username) {
            $updatePatientRequest->validate([
                'username' => 'unique:users,username,' . $user->id,
            ]);
        }
        if ($updatePatientRequest->username || $updatePatientRequest->email) {
            $updatePatientRequest->validate([
                'password' => 'required|min:8',
            ]);
        }

        $user->update([
            'name' => $updatePatientRequest->name,
            'username' => $updatePatientRequest->username,
            'phone_number' => $updatePatientRequest->phone_number,
            'birth_date' => $updatePatientRequest->birth_date,
            'address' => $updatePatientRequest->address,
            'email' => $updatePatientRequest->email,
            'password' => $updatePatientRequest->password ? Hash::make($updatePatientRequest->password) : $user->password,
        ]);

        return success(null, 'patient account updated successfully');
    }

    //Activate & Deactivate Patient Account Function
    public function activateDeactivateAccount(User $user, Request $request)
    {
        if ($user->status === 'activated') {
            $user->update([
                'status' => 'deactivated',
            ]);
            if ($request->type == 'web') {
                return redirect()->back()->with('success', 'patient account deactivated successfully');
            } else {
                return success(null, 'patient account deactivated successfully');
            }
        } else  if ($user->status === 'deactivated') {
            $user->update([
                'status' => 'activated',
            ]);

            return success(null, 'patient account activated successfully');
        }
    }

    //Get Patients Function
    public function getPatients(Request $request)
    {
        if ($request->status == null) {
            $patients = User::where('account_type', 'patient')->get();
        } else {
            $patients = User::where('account_type', 'patient')->where('status', $request->status)->get();
        }

        return success($patients, null);
    }

    //Get Patient Information Function
    public function getPatientInformation(User $user)
    {
        $birth_date = new DateTime($user->birth_date);
        $today = new DateTime();
        $age = $today->diff($birth_date)->y;
        $user["age"] = $age;
        $user = $user->with('record')->find($user->id);

        return success($user, null);
    }
}
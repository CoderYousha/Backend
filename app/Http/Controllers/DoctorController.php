<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    //Create Doctor Function
    public function createDoctor(DoctorRequest $doctorRequest)
    {
        User::create([
            'first_name' => $doctorRequest->first_name,
            'last_name' => $doctorRequest->last_name,
            'email' => $doctorRequest->email,
            'password' => Hash::make($doctorRequest->password),
            'phone_number' => $doctorRequest->phone_number,
            'account_type' => 'doctor',
            'medical_specialization' => $doctorRequest->medical_specialization,
            'work_section' => $doctorRequest->work_section,
            'birth_date' => $doctorRequest->birth_date,
            'address' => $doctorRequest->address,
        ]);

        return success(null, 'doctor account created successfully', 201);
    }

    //Edit Doctor Function
    public function editDoctor(User $user, UpdateDoctorRequest $updateDoctorRequest)
    {
        $updateDoctorRequest->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'first_name' => $updateDoctorRequest->first_name,
            'last_name' => $updateDoctorRequest->last_name,
            'email' => $updateDoctorRequest->email,
            'password' => Hash::check($updateDoctorRequest->password, $user->password) ? $user->password : Hash::make($updateDoctorRequest->password),
            'phone_number' => $updateDoctorRequest->phone_number,
            'medical_specialization' => $updateDoctorRequest->medical_specialization,
            'work_section' => $updateDoctorRequest->work_section,
            'birth_date' => $updateDoctorRequest->birth_date,
            'address' => $updateDoctorRequest->address,
        ]);

        return success(null, 'doctor account updated successfully');
    }

    //Delete Doctor Function
    public function deleteDoctor(User $user)
    {
        $user->delete();

        return success(null, 'this doctor deleted successfully');
    }

    //Get Doctors Function
    public function getDoctors()
    {
        $doctors = User::where('account_type', 'doctor')->get();

        return success($doctors, null);
    }

    //Get Doctor Information Function
    public function getDoctorInformation(User $user)
    {
        return success($user->with('holidays')->find($user->id), null);
    }
}
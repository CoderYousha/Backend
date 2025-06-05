<?php

namespace App\Http\Controllers;

use App\Models\DoctorPatient;
use App\Models\User;

class PatientTransferController extends Controller
{
    //Patient Transfer Function
    public function patientTransfer(User $patient, User $doctor)
    {
        DoctorPatient::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id
        ]);

        return success(null, 'patient transfered successfully', 201);
    }

    //Get User Transfers Function
    public function getUserTransfers(User $user)
    {
        $users = null;
        if ($user->account_type === 'doctor') {
            $users = $user->patients;
        } else if ($user->account_type === 'patient') {
            $users = $user->doctors;
        }

        return success($users, null);
    }
}
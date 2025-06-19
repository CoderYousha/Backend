<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\PatientBehavior;
use App\Models\PatientIll;
use App\Models\PatientMedicine;
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

        Patient::create([
            'name' => $patientRequest->name,
            'username' => $patientRequest->username,
            'email' => $patientRequest->email,
            'phone_number' => $patientRequest->phone_number,
            'birth_date' => $patientRequest->birth_date,
            'address' => $patientRequest->address,
            'password' => $patientRequest->password ? Hash::make($patientRequest->password) : null,
            'status' => 'activated',
        ]);

        return success(null, 'patient account created successfully', 201);
    }

    //Edit Patient Function
    public function editPatient(Patient $patient, UpdatePatientRequest $updatePatientRequest)
    {
        if ($updatePatientRequest->email) {
            $updatePatientRequest->validate([
                'email' => 'email|unique:users,email,' . $patient->id,
            ]);
        }
        if ($updatePatientRequest->username) {
            $updatePatientRequest->validate([
                'username' => 'unique:users,username,' . $patient->id,
            ]);
        }
        if ($updatePatientRequest->username || $updatePatientRequest->email) {
            $updatePatientRequest->validate([
                'password' => 'required|min:8',
            ]);
        }

        if ($updatePatientRequest->record_number) {
            $updatePatientRequest->validate([
                'record_number' => 'unique:patients,record_number,' . $patient->id,
            ]);
        }

        $patient->update([
            'name' => $updatePatientRequest->name,
            'username' => $updatePatientRequest->username,
            'phone_number' => $updatePatientRequest->phone_number,
            'birth_date' => $updatePatientRequest->birth_date,
            'address' => $updatePatientRequest->address,
            'email' => $updatePatientRequest->email,
            'password' => $updatePatientRequest->password ? Hash::make($updatePatientRequest->password) : $patient->password,
            'title' => $updatePatientRequest->title,
            'gender' => $updatePatientRequest->gender,
            'record_number' => $updatePatientRequest->record_number,
            'length' => $updatePatientRequest->length,
            'weight' => $updatePatientRequest->weight,
        ]);

        $ills = explode(',', $updatePatientRequest->ills);
        $medicines = explode(',', $updatePatientRequest->medicines);
        $behaviors = explode(',', $updatePatientRequest->behaviors);

        $checkIlls = PatientIll::where('patient_id', $patient->id)->whereNotIn('ill_id', $ills)->get();
        $checkMedicines = PatientMedicine::where('patient_id', $patient->id)->whereNotIn('medicine_id', $medicines)->get();
        $checkBehaviors = PatientBehavior::where('patient_id', $patient->id)->whereNotIn('behavior_id', $behaviors)->get();

        foreach ($ills as $ill) {
            if ($ill) {
                $check = PatientIll::where('patient_id', $patient->id)->where('ill_id', $ill)->first();
                if (!$check) {
                    PatientIll::create([
                        'patient_id' => $patient->id,
                        'ill_id' => $ill,
                    ]);
                }
            }
        }

        foreach ($medicines as $medicine) {
            if ($medicine) {
                $check = PatientMedicine::where('patient_id', $patient->id)->where('medicine_id', $medicine)->first();
                if (!$check) {
                    PatientMedicine::create([
                        'patient_id' => $patient->id,
                        'medicine_id' => $medicine,
                    ]);
                }
            }
        }

        foreach ($behaviors as $behavior) {
            if ($behavior) {
                $check = PatientBehavior::where('patient_id', $patient->id)->where('behavior_id', $behavior)->first();
                if (!$check) {
                    PatientBehavior::create([
                        'patient_id' => $patient->id,
                        'behavior_id' => $behavior,
                    ]);
                }
            }
        }

        foreach ($checkIlls as $ill) {
            $ill->delete();
        }
        foreach ($checkMedicines as $medicine) {
            $medicine->delete();
        }
        foreach ($checkBehaviors as $behavior) {
            $behavior->delete();
        }

        return success(null, 'patient account updated successfully');
    }

    //Activate & Deactivate Patient Account Function
    public function activateDeactivateAccount(Patient $patient, Request $request)
    {
        if ($patient->status === 'activated') {
            $patient->update([
                'status' => 'deactivated',
            ]);
            if ($request->type == 'web') {
                return redirect()->back()->with('success', 'patient account deactivated successfully');
            } else {
                return success(null, 'patient account deactivated successfully');
            }
        } else  if ($patient->status === 'deactivated') {
            $patient->update([
                'status' => 'activated',
            ]);

            return success(null, 'patient account activated successfully');
        }
    }

    //Get Patients Function
    public function getPatients(Request $request)
    {
        if ($request->status == null) {
            $patients = Patient::all();
        } else {
            $patients = Patient::where('status', $request->status)->get();
        }

        return success($patients, null);
    }

    //Get Patient Information Function
    public function getPatientInformation(Patient $patient)
    {
        $birth_date = new DateTime($patient->birth_date);
        $today = new DateTime();
        $age = $today->diff($birth_date)->y;
        $patient["age"] = $age;
        $patient = $patient->with('ills', 'medicines', 'behaviors', 'images')->find($patient->id);

        return success($patient, null);
    }
}
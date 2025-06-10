<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Models\MedicalRecord;
use App\Models\RecordBehavior;
use App\Models\RecordIll;
use App\Models\RecordMedicine;
use App\Models\User;

class MedicalRecordController extends Controller
{
    //Create Medical Record Function
    public function createMedicalRecord(User $user, MedicalRecordRequest $medicalRecordRequest)
    {
        $check = MedicalRecord::where('patient_id', $user->id)->first();
        if($check){
            return error('some thing went wrong', 'This patient already has a medical record', 422);
        }

        $medicalRecord = MedicalRecord::create([
            'patient_id' => $user->id,
            'record_number' => $medicalRecordRequest->record_number,
            'full_name' => $medicalRecordRequest->full_name,
            'gender' => $medicalRecordRequest->gender,
            'birth_date' => $medicalRecordRequest->birth_date,
            'national_number' => $medicalRecordRequest->national_number,
            'title' => $medicalRecordRequest->title,
            'phone_number' => $medicalRecordRequest->phone_number,
        ]);

        $ills = explode(',', $medicalRecordRequest->ills);
        $medicines = explode(',', $medicalRecordRequest->medicines);
        $behaviors = explode(',', $medicalRecordRequest->behaviors);

        foreach ($ills as $ill) {
            if ($ill)
                RecordIll::create([
                    'record_id' => $medicalRecord->id,
                    'ill_id' => $ill,
                ]);
        }

        foreach ($medicines as $medicine) {
            if ($medicine)
                RecordMedicine::create([
                    'record_id' => $medicalRecord->id,
                    'medicine_id' => $medicine,
                ]);
        }

        foreach ($behaviors as $behavior) {
            if ($behavior)
                RecordBehavior::create([
                    'record_id' => $medicalRecord->id,
                    'behavior' => $behavior,
                ]);
        }
        return success(null, 'created successfully!', 201);
    }

    //Update Medical Record Function
    public function updateMedicalRecord(MedicalRecord $medicalRecord, UpdateMedicalRecordRequest $updateMedicalRecordRequest)
    {
        $updateMedicalRecordRequest->validate([
            'record_number' => 'required|unique:medical_reports,record_number,' . $medicalRecord->id,
        ]);

        $medicalRecord->update([
            'record_number' => $updateMedicalRecordRequest->record_number,
            'full_name' => $updateMedicalRecordRequest->full_name,
            'gender' => $updateMedicalRecordRequest->gender,
            'birth_date' => $updateMedicalRecordRequest->birth_date,
            'national_number' => $updateMedicalRecordRequest->national_number,
            'title' => $updateMedicalRecordRequest->title,
            'phone_number' => $updateMedicalRecordRequest->phone_number,
        ]);

        $ills = explode(',', $updateMedicalRecordRequest->ills);
        $medicines = explode(',', $updateMedicalRecordRequest->medicines);
        $behaviors = explode(',', $updateMedicalRecordRequest->behaviors);

        $checkIlls = RecordIll::where('record_id', $medicalRecord->id)->whereIn('ill_id', $ills)->get();
        $checkMedicines = RecordMedicine::where('record_id', $medicalRecord->id)->whereNotIn('medicine_id', $medicines)->get();
        $checkBehaviors = RecordBehavior::where('record_id', $medicalRecord->id)->whereNotIn('behavior_id', $behaviors)->get();

        foreach ($ills as $ill) {
            if ($ill) {
                $check = RecordIll::where('record_id', $medicalRecord->id)->where('ill_id', $ill)->first();
                if (!$check) {
                    RecordIll::create([
                        'record_id' => $medicalRecord->id,
                        'ill_id' => $ill,
                    ]);
                }
            }
        }

        foreach ($medicines as $medicine) {
            if ($medicine) {
                $check = RecordMedicine::where('record_id', $medicalRecord->id)->where('medicine_id', $medicine)->first();
                if (!$check) {
                    RecordMedicine::create([
                        'record_id' => $medicalRecord->id,
                        'medicine_id' => $medicine,
                    ]);
                }
            }
        }

        foreach ($behaviors as $behavior) {
            if ($behavior) {
                $check = RecordBehavior::where('record_id', $medicalRecord->id)->where('behavior_id', $behavior)->first();
                if (!$check) {
                    RecordBehavior::create([
                        'record_id' => $medicalRecord->id,
                        'behavior' => $behavior,
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
        return success(null, 'updated successfully!');
    }

    //Delete Medical Record Function
    public function deleteMedicalRecord(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return success(null, 'deleted successfully!');
    }

    //Get Medical Record Information Function
    public function getMedicalRecordInformation(MedicalRecord $medicalRecord)
    {
        $medicalRecord = $medicalRecord->with('user', 'ills', 'medicines', 'behaviors')->find($medicalRecord->id);

        return success($medicalRecord, null);
    }
}
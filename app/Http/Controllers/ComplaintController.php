<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintRequest;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    //Get Complaints Function
    public function getComplaints()
    {
        $complaints = Complaint::with('patient')->get();

        return success($complaints, null);
    }

    //Get Complaint Information Function
    public function getComplaintInformation(Complaint $complaint)
    {
        return success($complaint->with('user')->find($complaint->id), null);
    }

    //Create Complaint Function
    public function createComplaint(ComplaintRequest $complaintRequest)
    {
        $user = Auth::guard('patient')->user();
        Complaint::create([
            'user_id' => $user->id,
            'title' => $complaintRequest->title,
            'complaint' => $complaintRequest->complaint,
        ]);

        return success(null, 'complaint created successfully', 201);
    }
}
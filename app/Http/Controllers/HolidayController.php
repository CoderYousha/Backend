<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{
    //Get Holidays Function
    public function getHolidays()
    {
        $holidays = Holiday::with('user')->get();

        return success($holidays, null);
    }

    //Get Employee Holidays Function
    public function getEmployeeHolidays()
    {
        $user = Auth::guard('user')->user();

        return success($user->holidays, null);
    }

    //Get Holiday Information Function
    public function getHolidayInformation(Holiday $holiday)
    {
        return success($holiday->with('user')->find($holiday->id), null);
    }

    //Accept Holiday Request Function
    public function acceptHolidayRequest(Holiday $holiday)
    {
        if ($holiday->status == 'accepted') {
            return error('some thing went wrong', 'this holiday had already accepted', 422);
        }

        $holiday->update([
            'status' => 'accepted',
        ]);

        return success(null, 'holiday accepted successfully');
    }

    //Reject Holiday Request Function
    public function rejectHolidayRequest(Holiday $holiday)
    {
        if ($holiday->status == 'rejected') {
            return error('some thing went wrong', 'this holiday had already rejected', 422);
        }

        $holiday->update([
            'status' => 'rejected',
        ]);

        return success(null, 'holiday rejected successfully');
    }

    //Create Holiday Request Function
    public function createHolidayRequest(HolidayRequest $holidayRequest)
    {
        $user = Auth::guard('user')->user();

        Holiday::create([
            'user_id' => $user->id,
            'reason' => $holidayRequest->reason,
            'start_date' => $holidayRequest->start_date,
            'end_date' => $holidayRequest->end_date,
        ]);

        return success(null, 'holiday request created successfully', 201);
    }

    //Edit Holiday Request Function
    public function editHolidayRequest(Holiday $holiday, HolidayRequest $holidayRequest)
    {
        if ($holiday->status != 'pending') {
            return error('some thing went wrong', "this holiday can't update now", 422);
        }
        $holiday->update([
            'reason' => $holidayRequest->reason,
            'start_date' => $holidayRequest->start_date,
            'end_date' => $holidayRequest->end_date,
        ]);

        return success(null, 'holiday request updated successfully');
    }

    //Delete Holiday Request Function
    public function deleteHolidayRequest(Holiday $holiday)
    {
        if ($holiday->status != 'pending') {
            return error('some thing went wrong', "this holiday can't update now", 422);
        }

        $holiday->delete();

        return success(null, 'holiday request deleted successfully');
    }
}
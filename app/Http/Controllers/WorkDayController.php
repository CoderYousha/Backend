<?php

namespace App\Http\Controllers;

use App\Models\User;

class WorkDayController extends Controller
{
    //Get Doctor Work Days Function
    public function getDoctorWorkDays(User $user)
    {
        $workDayas = $user->workDays;
        return success($workDayas, null);
    }
}
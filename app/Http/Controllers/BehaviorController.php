<?php

namespace App\Http\Controllers;

use App\Models\HealthyBehaviors;

class BehaviorController extends Controller
{
    //Get Healthy Behaviors Function
    public function getHealthyBehaviors()
    {
        $behaviors = HealthyBehaviors::all();
        return success($behaviors, null);
    }
}
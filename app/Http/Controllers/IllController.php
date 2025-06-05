<?php

namespace App\Http\Controllers;

use App\Models\Illnes;

class IllController extends Controller
{
    //Get Ills Function
    public function getIlls()
    {
        $ills = Illnes::all();
        return success($ills, null);
    }
}
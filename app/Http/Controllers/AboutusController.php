<?php

namespace App\Http\Controllers;

use App\Models\Aboutus;
use Illuminate\Http\Request;

class AboutusController extends Controller
{
    //Get Aboutus Function
    public function getAboutus()
    {
        $aboutus = Aboutus::first();

        return success($aboutus, null);
    }
}
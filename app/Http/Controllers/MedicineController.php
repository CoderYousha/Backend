<?php

namespace App\Http\Controllers;

use App\Models\Medicine;

class MedicineController extends Controller
{
    //Get Medicines Function
    public function getMedicines()
    {
        $medicines = Medicine::all();

        return success($medicines, null);
    }
}
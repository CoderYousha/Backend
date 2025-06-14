<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TechnicalController extends Controller
{
    //Upload Image Function
    public function uploadImage(Patient $patient, Request $request)
    {
        $path = null;
        $request->validate([
            'image' => 'required|image',
        ]);
        $user = Auth::guard('user')->user();
        if ($request->file('image')) {
            $path = $request->file('image')->storePublicly($user->medical_specialization == 'Technical' ? 'TechnicalImages' : 'RadiationImages', 'public');
        }

        Image::create([
            'patient_id' => $patient->id,
            'image' => 'storage/' . $path,
            'description' => $request->description,
            'type' => $user->medical_specialization,
        ]);

        return success(null, 'image uploaded successfully', 201);
    }

    //Delete Image Function
    public function deleteImage(Image $image)
    {
        if (File::exists($image->image)) {
            File::delete($image->image);
        }

        $image->delete();
        return success(null, 'image deleted successfully');
    }

    //Get Image Information Function
    public function getImageInformation(Image $image)
    {
        return success($image, null);
    }
}
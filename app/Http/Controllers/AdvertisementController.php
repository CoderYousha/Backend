<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdvertisementController extends Controller
{
    //Create Advertisement Function
    public function createAdvertisement(AdvertisementRequest $advertisementRequest)
    {
        if ($advertisementRequest->file('image')) {
            $path = $advertisementRequest->file('image')->storePublicly('AdvertisementsImages', 'public');
        }
        Advertisement::create([
            'title' => $advertisementRequest->title,
            'description' => $advertisementRequest->description,
            'image' => 'storage/' . $path,
        ]);

        return success(null, 'advertisement created successfully', 201);
    }

    //Edit Advertisement Function
    public function editAdvertisement(Advertisement $advertisement, UpdateAdvertisementRequest $updateAdvertisementRequest)
    {
        if ($updateAdvertisementRequest->file('image')) {
            if (File::exists($advertisement->image)) {
                File::delete($advertisement->image);
            }
            $path = $updateAdvertisementRequest->file('image')->storePublicly('AdvertisementsImages', 'public');
            $advertisement->update([
                'image' => 'storage/' . $path,
            ]);
        }
        $advertisement->update([
            'title' => $updateAdvertisementRequest->title,
            'description' => $updateAdvertisementRequest->description,
        ]);

        return success(null, 'advertisement updated successfully');
    }

    //Delete Advertisement Function
    public function deleteAdvertisement(Advertisement $advertisement)
    {
        if (File::exists($advertisement->image)) {
            File::delete($advertisement->image);
        }

        $advertisement->delete();

        return success(null, 'advertisement deleted successfully');
    }

    //Get Advertisements Function
    public function getAdvertisements()
    {
        $advertisements = Advertisement::all();

        return success($advertisements, null);
    }

    //Get Advertisement Information Function
    public function getAdvertisementInformation(Advertisement $advertisement)
    {
        return success($advertisement, null);
    }
}
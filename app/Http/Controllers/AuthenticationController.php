<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Patient;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    //Login Function
    public function login(LoginRequest $loginRequest)
    {
        $fieldType = filter_var($loginRequest->email_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($loginRequest->type == 'patient') {
            $user = Patient::where($fieldType, $loginRequest->email_username)->first();
            if ($user && Hash::check($loginRequest->password, $user->password)) {
                $token = $user->createToken('patient')->plainTextToken;
                if ($user->status === 'deactivated') {
                    return error('some thing went wrong', 'your account deactivated', 422);
                }
                return success($token, 'login successfully');
            }

            return error('some thing went wrong', 'incorrect email or password', 422);
        } else {
            $user = User::where($fieldType, $loginRequest->email_username)->first();
            if ($user && Hash::check($loginRequest->password, $user->password)) {
                $token = $user->createToken('user')->plainTextToken;
                if ($user->status === 'deactivated') {
                    return error('some thing went wrong', 'your account deactivated', 422);
                }
                return success($token, 'login successfully');
            }

            return error('some thing went wrong', 'incorrect email or password', 422);
        }
    }

    //Logout Function
    public function logout()
    {
        $user = Auth::guard('patient')->user();
        if ($user) {
            $user->tokens()->delete();
            return success(null, 'logout successfully');
        } else {
            $user = Auth::guard('user')->user();
            $user->tokens()->delete();
            return success(null, 'logout successfully');
        }
    }

    //Profile Function
    public function profile()
    {
        $user = Auth::guard('patient')->user()->load('ills','medicines','behaviors','images');
        if (!$user) {
            $user = Auth::guard('user')->user();
        }

        $birth_date = new DateTime($user->birth_date);
        $today = new DateTime();
        $age = $today->diff($birth_date)->y;
        $user["age"] = $age;
        return success($user, null);
    }

    //Edit Profile Function
    public function editProfile(ProfileRequest $profileRequest)
    {
        $user = Auth::guard('user')->user();

        if (!$user) {
            $user = Auth::guard('patient')->user();
        }

        $user->update([
            'name' => $profileRequest->name,
            'phone_number' => $profileRequest->phone_number,
            'birth_date' => $profileRequest->birth_date,
            'address' => $profileRequest->address,
        ]);

        return success(null, 'your profile updated successfully');
    }

    //Edit Password Function
    public function editPassword(EditPasswordRequest $editPasswordRequest)
    {
        $user = Auth::guard('user')->user();

        if (!$user) {
            $user = Auth::guard('patient')->user();
        }

        if (Hash::check($editPasswordRequest->password, $user->password)) {
            if ($editPasswordRequest->new_password == $editPasswordRequest->confirm_password) {
                $user->update([
                    'password' => Hash::make($editPasswordRequest->new_password),
                ]);

                return success(null, 'your password updated successfully');
            }

            return error('some thing went wrong', 'incorrect password confirmation', 422);
        }

        return error('some thing went wrong', 'incorrect password', 422);
    }
}
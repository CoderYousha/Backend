<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
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
        if ($loginRequest->type == 'web') {
            if (Auth::attempt([$fieldType => $loginRequest->email_username, "password" => $loginRequest->password])) {
                return view("");
            }
            return redirect()->back()->withErrors(['login' => 'incorrect ' . $fieldType . ' or password']);
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
    public function logout(Request $request)
    {
        $user = Auth::guard('user')->user();
        if ($request->type == "web") {
            $user->logout();
            return view("");
        } else {
            $user->tokens()->delete();
            return success(null, 'logout successfully');
        }
    }

    //Profile Function
    public function profile(Request $request)
    {
        $user = Auth::guard('user')->user();
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

        $user->update([
            'first_name' => $profileRequest->first_name,
            'last_name' => $profileRequest->last_name,
            'phone_number' => $profileRequest->phone_number,
            'birth_date' => $profileRequest->birth_date,
            'address' => $profileRequest->address,
        ]);

        return success(null, 'your profile updated successfully');
    }
}

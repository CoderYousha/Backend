<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    //Create Employee Function
    public function createEmployee(EmployeeRequest $employeeRequest)
    {
        User::create([
            'first_name' => $employeeRequest->first_name,
            'last_name' => $employeeRequest->last_name,
            'email' => $employeeRequest->email,
            'password' => Hash::make($employeeRequest->password),
            'phone_number' => $employeeRequest->phone_number,
            'account_type' => $employeeRequest->account_type,
            'medical_specialization' => $employeeRequest->medical_specialization,
            'work_section' => $employeeRequest->work_section,
            'birth_date' => $employeeRequest->birth_date,
            'address' => $employeeRequest->address,
        ]);

        return success(null, 'employee account created successfully', 201);
    }

    //Edit Employee Function
    public function editEmployee(User $user, UpdateEmployeeRequest $updateEmployeeRequest)
    {
        $updateEmployeeRequest->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'first_name' => $updateEmployeeRequest->first_name,
            'last_name' => $updateEmployeeRequest->last_name,
            'email' => $updateEmployeeRequest->email,
            'password' => Hash::check($updateEmployeeRequest->password, $user->password) ? $user->password : Hash::make($updateEmployeeRequest->password),
            'phone_number' => $updateEmployeeRequest->phone_number,
            'account_type' => $updateEmployeeRequest->account_type,
            'work_section' => $updateEmployeeRequest->work_section,
            'birth_date' => $updateEmployeeRequest->birth_date,
            'address' => $updateEmployeeRequest->address,
        ]);

        return success(null, 'employee account updated successfully');
    }

    //Delete Employee Function
    public function deleteEmployee(User $user)
    {
        $user->delete();

        return success(null, 'this employee deleted successfully');
    }

    //Get Employees Function
    public function getEmployees()
    {
        $employees = User::whereNotIn('account_type', ['admin', 'patient', 'doctor'])->get();

        return success($employees, null);
    }

    //Get Employee Information Function
    public function getEmployeeInformation(User $user)
    {
        return success($user, null);
    }

    //Get Employees Counts Function
    public function getEmployeesCounts()
    {
        $employees = User::whereNotIn('account_type', ['admin', 'patient', 'doctor'])->get();
        $count = count($employees);

        return success($count, null);
    }
}
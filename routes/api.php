<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BehaviorController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\IllController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientTransferController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WorkDayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/login', 'login');
    Route::prefix('profile')->middleware('check-auth')->group(function () {
        Route::get('/', 'profile');
        Route::post('/', 'editProfile');
        Route::post('/logout', 'logout');
    });
});

Route::middleware('check-auth')->group(function () {
    Route::controller(DoctorController::class)->prefix('doctors')->group(function () {
        Route::middleware('admin-auth')->group(function () {
            Route::post('/', 'createDoctor');
            Route::post('/{user}', 'editDoctor');
            Route::delete('/{user}', 'deleteDoctor');
        });
    });
    Route::controller(EmployeeController::class)->prefix('employees')->group(function () {
        Route::get('/', 'getEmployees');
        Route::get('/{user}', 'getEmployeeInformation');
        Route::middleware('admin-auth')->group(function () {
            Route::post('/', 'createEmployee');
            Route::post('/{user}', 'editEmployee');
            Route::delete('/{user}', 'deleteEmployee');
        });
    });
    Route::get('/employees-counts', [EmployeeController::class, 'getEmployeesCounts']);
    Route::controller(ClinicController::class)->prefix('clinics')->group(function () {
        Route::middleware('admin-auth')->group(function () {
            Route::post('/', 'createClinic');
            Route::post('/{clinic}', 'editClinic');
            Route::post('/{clinic}/{user}', 'addDoctorToClinic');
            Route::delete('/{clinic}/{user}', 'removeDoctorFromClinic');
            Route::delete('/{clinic}', 'deleteClinic');
        });
    });
    Route::controller(ComplaintController::class)->prefix('complaints')->group(function () {
        Route::middleware('admin-auth')->group(function () {
            Route::get('/', 'getComplaints');
            Route::get('/{complaint}', 'getComplaintInformation');
        });
        Route::middleware('patient-auth')->group(function () {
            Route::post('/', 'createComplaint');
        });
    });
    Route::controller(HolidayController::class)->prefix('holidays')->group(function () {
        Route::middleware('admin-auth')->group(function () {
            Route::get('/', 'getHolidays');
            Route::get('/{holiday}', 'getHolidayInformation');
            Route::post('/accept/{holiday}', 'acceptHolidayRequest');
            Route::post('/reject/{holiday}', 'rejectHolidayRequest');
        });
        Route::middleware('employee-auth')->group(function () {
            Route::get('/employee', 'getEmployeeHolidays');
            Route::get('/employee/{holiday}', 'getHolidayInformation');
            Route::post('/request', 'createHolidayRequest');
            Route::post('/request/{holiday}', 'editHolidayRequest');
            Route::delete('/request/{holiday}', 'deleteHolidayRequest');
        });
    });
    Route::controller(HolidayController::class)->middleware('employee-auth')->prefix('employees-holidays')->group(function () {
        Route::get('/', 'getEmployeeHolidays');
        Route::get('/{holiday}', 'getHolidayInformation');
    });
    Route::controller(AdvertisementController::class)->middleware('advertisement-auth')->prefix('advertisements')->group(function () {
        Route::post('/', 'createAdvertisement');
        Route::post('/{advertisement}', 'editAdvertisement');
        Route::delete('/{advertisement}', 'deleteAdvertisement');
    });
    Route::controller(PatientController::class)->middleware('reception-auth')->prefix('patients')->group(function () {
        Route::get('/', 'getPatients');
        Route::get('/{user}', 'getPatientInformation');
        Route::post('/', 'createPatient');
        Route::post('/{user}', 'editPatient');
        Route::post('/activate-deactivate/{user}', 'activateDeactivateAccount');
    });
    Route::controller(PatientTransferController::class)->prefix('transfers')->group(function () {
        Route::get('/{user}', 'getUserTransfers')->middleware('transfer-auth');
        Route::post('/{patient}/{doctor}', 'patientTransfer')->middleware('reception-auth');
    });
    Route::controller(IllController::class)->prefix('ills')->group(function () {
        Route::get('/', 'getIlls');
    });
    Route::controller(MedicineController::class)->prefix('medicines')->group(function () {
        Route::get('/', 'getMedicines');
    });
    Route::controller(BehaviorController::class)->prefix('behaviors')->group(function () {
        Route::get('/', 'getHealthyBehaviors');
    });
    Route::controller(MedicalRecordController::class)->prefix('medical-records')->group(function () {
        Route::get('/{medicalRecord}', 'getMedicalRecordInformation');
        Route::post('/{user}', 'createMedicalRecord');
        Route::post('/update/{medicalRecord}', 'updateMedicalRecord');
        Route::delete('/{medicalRecord}', 'deleteMedicalRecord');
    });
    Route::controller(WorkDayController::class)->prefix('work-days')->group(function () {
        Route::get('/{user}', 'getDoctorWorkDays');
    });
    Route::controller(ReservationController::class)->prefix('reservations')->group(function () {
        Route::get('/{user}', 'getReservations');
        Route::middleware('reservation-auth')->group(function () {
            Route::post('/{doctor}/{patient}', 'createReservation');
            Route::delete('/{reservation}', 'cancelReservation');
        });
    });
});
Route::controller(AdvertisementController::class)->middleware('check-auth')->prefix('advertisements')->group(function () {
    Route::get('/', 'getAdvertisements');
    Route::get('/{advertisement}', 'getAdvertisementInformation');
});
Route::controller(ClinicController::class)->prefix('clinics')->group(function () {
    Route::get('/', 'getClinics');
    Route::get('/{clinic}', 'getClinicInformation');
});
Route::controller(DoctorController::class)->prefix('doctors')->group(function () {
    Route::get('/', 'getDoctors');
    Route::get('/{user}', 'getDoctorInformation');
});
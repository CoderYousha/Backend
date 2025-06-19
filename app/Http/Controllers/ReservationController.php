<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditReservationRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    //Create Resservation Function
    public function createReservation(User $doctor, Patient $patient, ReservationRequest $reservationRequest)
    {
        $user = Auth::guard('user')->user();
        $day = Carbon::parse($reservationRequest->date)->translatedFormat('l');
        foreach ($doctor->holidays as $holiday) {
            if ($holiday->start_date <= $reservationRequest->date && $holiday->end_date >= $reservationRequest->date) {
                return error('some thing went wrong', 'doctor in holiday!', 422);
            }
        }

        foreach ($doctor->workDays as $workDay) {
            if ($day == $workDay->day) {
                foreach ($doctor->doctorReservations as $reservation) {
                    if ($reservationRequest->time == $reservation->time) {
                        return error('some thing went wrong', 'you cannot reserve in this time', 422);
                    }
                }

                Reservation::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patient->id,
                    'clinic_transfer_id' => $user && $user->account_type == 'doctor' ? $user->clinic->clinic_id : $reservationRequest->clinic_id,
                    'date' => Carbon::parse($reservationRequest->date),
                    'time' => $reservationRequest->time,
                    'status' => 'activated',
                    'type' => $reservationRequest->type,
                    'description' => $reservationRequest->description,
                ]);

                return success(null, 'your reservation created successfully', 201);
            }
        }
        return error('some thing went wrong', 'doctor dont work in this day', 422);
    }

    //Edit Reservation Function
    public function editReservation(Reservation $reservation, EditReservationRequest $editReservationRequest)
    {
        $day = Carbon::parse($editReservationRequest->date)->translatedFormat('l');
        $doctor = User::find($editReservationRequest->doctor_id);
        $user = Auth::guard('user')->user();
        if ($reservation->doctor_id != $doctor->id || Carbon::parse($reservation->date) != Carbon::parse($editReservationRequest->date) || $reservation->time != $editReservationRequest->time) {
            foreach ($doctor->holidays as $holiday) {
                if ($holiday->start_date <= $editReservationRequest->date && $holiday->end_date >= $editReservationRequest->date) {
                    return error('some thing went wrong', 'doctor in holiday!', 422);
                }
            }
            foreach ($doctor->workDays as $workDay) {
                if ($day == $workDay->day) {
                    foreach ($doctor->doctorReservations as $reservation) {
                        if ($editReservationRequest->time == $reservation->time) {
                            return error('some thing went wrong', 'you cannot reserve in this time', 422);
                        }
                    }
                }
            }
        }


        $reservation->update([
            'doctor_id' => $doctor->id,
            'patient_id' => $editReservationRequest->patient_id,
            'date' => Carbon::parse($editReservationRequest->date),
            'time' => $editReservationRequest->time,
            'status' => 'activated',
            'type' => $editReservationRequest->type,
            'description' => $editReservationRequest->description,
        ]);

        return success(null, 'reservation updated successfully', 201);
    }

    //Canceling Reservation Function
    public function cancelReservation(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'canceled'
        ]);

        return success(null, 'this reservation canceled successfully');
    }

    //Get User Reservations Function
    public function getReservations(Request $request)
    {
        $user = Auth::guard('user')->user();
        if ($user) {
            if ($user->account_type == 'reception') {
                $reservations = Reservation::with('patient', 'doctor', 'clinicTransfer')->get();
            } else if ($user->account_type == 'doctor') {
                $reservations = $user->doctorReservations()->with('patient', 'clinicTransfer')->get();
            }
        } else {
            $user = Auth::guard('patient')->user();
            $reservations = $user->reservations()->with('clinicTransfer', 'doctor')->where('status', 'LIKE', '%' . $request->status . '%')->get();
        }
        return success($reservations, null);
    }

    //Get Reservation Information Function
    public function getReservationInformation(Reservation $reservation)
    {
        $reservation = $reservation->with('doctor', 'patient', 'clinicTransfer')->find($reservation->id);

        return success($reservation, null);
    }
}

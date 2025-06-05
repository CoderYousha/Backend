<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class ReservationController extends Controller
{
    //Create Resservation Function
    public function createReservation(User $doctor, User $patient, ReservationRequest $reservationRequest)
    {
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
                    'date' => Carbon::parse($reservationRequest->date),
                    'time' => $reservationRequest->time,
                ]);

                return success(null, 'your reservation created successfully', 201);
            }
        }
        return error('some thing went wrong', 'doctor dont work in this day', 422);
    }

    //Canceling Reservation Function
    public function cancelReservation(Reservation $reservation)
    {
        $reservation->delete();

        return success(null, 'this reservation deleted successfully');
    }

    //Get User Reservations Function
    public function getReservations(User $user)
    {
        if ($user->account_type == 'patient') {
            $reservations = $user->patientReservations()->with('doctor')->get();

            return success($reservations, null);
        } else if ($user->account_type == 'doctor') {
            $reservations = $user->doctorReservations()->with('patient')->get();

            return success($reservations, null);
        }
    }
}
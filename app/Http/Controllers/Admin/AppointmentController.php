<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $allAppointments = Appointment::with('user')
            ->orderBy('requested_datetime')
            ->get();

        $todayAppointments = Appointment::with('user')
            ->whereDate('requested_datetime', Carbon::today())
            ->orderBy('requested_datetime')
            ->get();

        $weekAppointments = Appointment::with('user')
            ->whereBetween('requested_datetime', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('requested_datetime')
            ->get();

        return view('admin.appointments.all', compact('allAppointments', 'todayAppointments', 'weekAppointments'));
    }

    public function today()
    {
        $appointments = Appointment::whereDate('requested_datetime', Carbon::today())
            ->orderBy('requested_datetime')
            ->get();

        return view('admin.appointments.today', compact('appointments'));
    }

    public function week()
    {
        $appointments = Appointment::whereBetween('requested_datetime', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('requested_datetime')
            ->get();

        return view('admin.appointments.week', compact('appointments'));
    }
}

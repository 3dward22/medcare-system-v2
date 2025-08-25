<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Patient;
class DashboardController extends Controller
{
     public function index()
    {
        $medicineCount = Medicine::count();
        $appointmentCount = Appointment::count();
        $patientCount = Patient::count();

        return view('dashboard', compact('medicineCount', 'appointmentCount', 'patientCount'));
    }
}

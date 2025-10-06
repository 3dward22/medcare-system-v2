<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generateMonthlyReport()
    {
        $month = Carbon::now()->format('F Y');
        $appointments = Appointment::whereMonth('requested_datetime', Carbon::now()->month)->get();

        $data = [
            'month' => $month,
            'total' => $appointments->count(),
            'approved' => $appointments->where('status', 'approved')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'declined' => $appointments->where('status', 'declined')->count(),
            'appointments' => $appointments,
        ];

        $pdf = Pdf::loadView('reports.monthly', $data)->setPaper('a4', 'portrait');
        return $pdf->download("MedCare_Report_{$month}.pdf");
    }
}

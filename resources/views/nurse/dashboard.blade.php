@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-2">👩‍⚕️ Nurse Dashboard</h1>
    <p class="text-muted">
        Welcome, {{ auth()->user()->name }}! You are logged in as 
        <strong>{{ auth()->user()->role }}</strong>.
    </p>

    <!-- Today's Appointments Summary -->
    <div class="my-3">
        <h5>📅 You have <strong>{{ $todayAppointments->count() }}</strong> appointment(s) today</h5>
    </div>

    <!-- Today's Appointments Table -->
    <div class="card shadow-sm rounded-4 mt-3">
        <div class="card-body">
            <h4 class="card-title mb-3">📋 Today's Appointments</h4>

            @if ($todayAppointments->count() > 0)
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Patient Name</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($todayAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->student_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($appointment->status === 'pending') bg-warning
                                        @elseif($appointment->status === 'approved') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted text-center my-3">
                    No appointments scheduled for today.
                </p>
            @endif
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <!-- Appointments Management -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📅 Manage All Appointments</h5>
                    <p class="card-text">View and manage all upcoming appointments.</p>
                    <a href="{{ route('nurse.appointments.index') }}" class="btn btn-success">Open</a>
                </div>
            </div>
        </div>

        <!-- Student Records -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📖 Student Records</h5>
                    <p class="card-text">View student medical history and profiles.</p>
                    <a href="{{ route('nurse.students.index') }}" class="btn btn-info">Open</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

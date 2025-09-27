@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🎓 Student Dashboard</h1>
    <p class="text-muted">
        Welcome, {{ auth()->user()->name }}! Here are your upcoming appointments:
    </p>

    @if ($appointments->count() > 0)
        <table class="table table-hover mt-4">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</td>
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
        <p class="text-muted mt-4">No upcoming appointments.</p>
    @endif
    <button type="submit" class="btn btn-primary">Book Appointment</button>
</div>
@endsection

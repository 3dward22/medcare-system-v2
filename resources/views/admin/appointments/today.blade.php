@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📅 This Week's Appointments</h1>
        <div>
            <a href="{{ route('admin.appointments.all') }}" 
               class="btn {{ Route::is('admin.appointments.all') ? 'btn-primary' : 'btn-outline-primary' }} me-2">
               All
            </a>
            <a href="{{ route('admin.appointments.today') }}" 
               class="btn {{ Route::is('admin.appointments.today') ? 'btn-success' : 'btn-outline-success' }} me-2">
               Today
            </a>
            <a href="{{ route('admin.appointments.week') }}" 
               class="btn {{ Route::is('admin.appointments.week') ? 'btn-warning text-white' : 'btn-outline-warning' }}">
               This Week
            </a>
        </div>
    </div>

    @if($appointments->count() > 0)
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->user->name ?? 'Unknown' }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
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
        <p class="text-muted text-center my-3">No appointments scheduled for this week.</p>
    @endif
</div>
@endsection

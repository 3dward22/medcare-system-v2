@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📅 This Week's Appointments</h1>

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
                        <td>{{ $appointment->student_name }}</td>
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

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Appointment Details</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $appointment->id }}</p>
            <p><strong>Student:</strong> {{ $appointment->student_name ?? 'N/A' }}</p>
            <p><strong>Date & Time:</strong> {{ $appointment->scheduled_at ?? 'N/A' }}</p>
            <p><strong>Notes:</strong> {{ $appointment->notes ?? 'No notes.' }}</p>
        </div>
    </div>

    <a href="{{ route('appointments.index') }}" class="btn btn-secondary mt-3">Back to Appointments</a>
</div>
@endsection

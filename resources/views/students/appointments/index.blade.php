@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Your Appointments</h1>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Appointment Form --}}
    <div class="card mb-4">
        <div class="card-header">Book New Appointment</div>
        <div class="card-body">
            <form action="{{ route('nurse.appointments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" name="time" id="time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Book Appointment</button>
            </form>
        </div>
    </div>

    {{-- List of Appointments --}}
    <div class="card">
        <div class="card-header">My Appointments</div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->date }}</td>
                            <td>{{ $appointment->time }}</td>
                            <td>
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                {{-- Add edit/delete buttons if needed --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No appointments booked yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection


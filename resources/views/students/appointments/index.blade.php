@extends('layouts.app')

@section('content')
<div class="container">
    <h3>My Appointments</h3>

    @if($appointments->isEmpty())
        <p class="text-muted">You don’t have any appointments yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>{{ $appointment->reason }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
@endsection

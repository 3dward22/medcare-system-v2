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
        <div class="card-header">📅 Request New Appointment</div>
        <div class="card-body">
            <form action="{{ route('student.appointments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="requested_datetime" class="form-label">Choose Date & Time</label>
                    <input type="datetime-local" name="requested_datetime" id="requested_datetime" 
                           class="form-control @error('requested_datetime') is-invalid @enderror" required>
                    @error('requested_datetime')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Request Appointment</button>
            </form>
        </div>
    </div>

    {{-- List of Appointments --}}
    <div class="card">
        <div class="card-header">My Appointments</div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Approved Date</th>
                                <th>Admin Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $appointment->status === 'approved' ? 'success' : 
                                        ($appointment->status === 'pending' ? 'warning' : 
                                        ($appointment->status === 'declined' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>{{ $appointment->approved_datetime ? \Carbon\Carbon::parse($appointment->approved_datetime)->format('M d, Y h:i A') : '-' }}</td>
                                <td>{{ $appointment->admin_note ?? '-' }}</td>
                                <td>
                                    @if($appointment->status === 'pending')
                                        <form action="{{ route('student.appointments.destroy', $appointment->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    @else
                                        <em>No action</em>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No appointments booked yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection

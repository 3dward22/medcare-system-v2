@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📋 Manage All Appointments</h2>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <div class="mb-3">
        <a href="{{ route('admin.appointments.all') }}" class="btn btn-outline-primary btn-sm">All</a>
        <a href="{{ route('admin.appointments.today') }}" class="btn btn-outline-success btn-sm">Today</a>
        <a href="{{ route('admin.appointments.week') }}" class="btn btn-outline-info btn-sm">This Week</a>
    </div>

    @if($allAppointments->count() > 0)
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Student</th>
                    <th>Requested Date</th>
                    <th>Status</th>
                    <th>Approved Date</th>
                    <th>Admin Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allAppointments as $appointment)
                <tr>
                    <td>{{ $appointment->student->name }}</td>
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
                        {{-- Approve / Reschedule / Decline --}}
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#manageModal{{ $appointment->id }}">
                            Manage
                        </button>
                    </td>
                </tr>

                {{-- Manage Modal --}}
                <div class="modal fade" id="manageModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="manageModalLabel{{ $appointment->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('admin.appointments.update', $appointment->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="manageModalLabel{{ $appointment->id }}">Manage Appointment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Approved Date & Time</label>
                                        <input type="datetime-local" class="form-control" name="approved_datetime" value="{{ $appointment->approved_datetime }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" @selected($appointment->status === 'pending')>Pending</option>
                                            <option value="approved" @selected($appointment->status === 'approved')>Approved</option>
                                            <option value="rescheduled" @selected($appointment->status === 'rescheduled')>Rescheduled</option>
                                            <option value="declined" @selected($appointment->status === 'declined')>Declined</option>
                                            <option value="completed" @selected($appointment->status === 'completed')>Completed</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Admin Note</label>
                                        <textarea name="admin_note" class="form-control" rows="2">{{ $appointment->admin_note }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No appointments found.</p>
    @endif
</div>
@endsection

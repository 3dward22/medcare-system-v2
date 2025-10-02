@extends('layouts.app')

@section('content')
<div class="container">

    <!-- 🔗 Quick navigation buttons -->
    <div class="mb-3">
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📅 All Appointments</h2>
        
    </div>

    {{-- All Appointments Table --}}
    <div class="card shadow">
        <div class="card-body">
            @if($allAppointments->isEmpty())
                <p class="text-center text-muted">No appointments found.</p>
            @else
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Requested Date</th>
                        <th>Approved Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allAppointments as $appointment)
                    <tr>
                        <td>{{ $appointment->student->name ?? 'Unknown' }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A') }}</td>
                        <td>
                            @if($appointment->approved_datetime)
                                {{ \Carbon\Carbon::parse($appointment->approved_datetime)->format('M d, Y h:i A') }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                @if($appointment->status === 'pending') bg-warning
                                @elseif($appointment->status === 'approved') bg-success
                                @elseif($appointment->status === 'rescheduled') bg-info
                                @elseif($appointment->status === 'declined') bg-danger
                                @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#manageAppointmentModal"
                                    data-id="{{ $appointment->id }}"
                                    data-approved_datetime="{{ $appointment->approved_datetime }}"
                                    data-status="{{ $appointment->status }}"
                                    data-note="{{ $appointment->admin_note }}">
                                Manage
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Today Modal --}}
    <div class="modal fade" id="todayModal" tabindex="-1" aria-labelledby="todayModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="todayModalLabel">Today's Appointments</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            @if($todayAppointments->isEmpty())
                <p class="text-center text-muted">No appointments for today.</p>
            @else
                <ul class="list-group">
                    @foreach($todayAppointments as $appointment)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $appointment->student->name ?? 'Unknown' }}</span>
                        <span>{{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('h:i A') }}</span>
                    </li>
                    @endforeach
                </ul>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Week Modal --}}
    <div class="modal fade" id="weekModal" tabindex="-1" aria-labelledby="weekModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="weekModalLabel">This Week's Appointments</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            @if($weekAppointments->isEmpty())
                <p class="text-center text-muted">No appointments this week.</p>
            @else
                <ul class="list-group">
                    @foreach($weekAppointments as $appointment)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $appointment->student->name ?? 'Unknown' }}</span>
                        <span>{{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('D, M d h:i A') }}</span>
                    </li>
                    @endforeach
                </ul>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Manage Modal --}}
    <div class="modal fade" id="manageAppointmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="manageAppointmentForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Manage Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="approved_datetime" class="form-label">Approved Date & Time</label>
                            <input type="datetime-local" name="approved_datetime" id="approved_datetime" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="approved">Approve</option>
                                <option value="rescheduled">Reschedule</option>
                                <option value="declined">Decline</option>
                                <option value="completed">Mark as Completed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="admin_note" class="form-label">Admin Note</label>
                            <textarea name="admin_note" id="admin_note" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    const manageModal = document.getElementById('manageAppointmentModal');
    manageModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-id');
        let approvedDatetime = button.getAttribute('data-approved_datetime');
        let status = button.getAttribute('data-status');
        let note = button.getAttribute('data-note');

        let form = document.getElementById('manageAppointmentForm');
        form.action = "{{ url('/admin/appointments') }}/" + id;

        document.getElementById('approved_datetime').value = approvedDatetime
            ? approvedDatetime.replace(' ', 'T')
            : '';
        document.getElementById('status').value = status;
        document.getElementById('admin_note').value = note ?? '';
    });
</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">📅 Manage Appointments</h3>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($appointments->isEmpty())
        <p class="text-muted">No appointments found.</p>
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
                @foreach($appointments as $appointment)
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
                            <!-- Button to open modal -->
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

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $appointments->links() }}
        </div>
    @endif
</div>

<!-- Modal for managing appointment -->
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

<script>
    const manageModal = document.getElementById('manageAppointmentModal');
    manageModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-id');
        let approvedDatetime = button.getAttribute('data-approved_datetime');
        let status = button.getAttribute('data-status');
        let note = button.getAttribute('data-note');

        let form = document.getElementById('manageAppointmentForm');
        form.action = "{{ url('/nurse/appointments') }}/" + id;

        // ✅ Automatically set approved_datetime to current time if empty
        const approvedInput = document.getElementById('approved_datetime');
        if (!approvedDatetime) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            approvedInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        } else {
            approvedInput.value = approvedDatetime.replace(' ', 'T');
        }

        document.getElementById('status').value = status;
        document.getElementById('admin_note').value = note ?? '';
    });
</script>

@endsection

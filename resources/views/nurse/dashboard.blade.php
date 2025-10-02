@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-2">👩‍⚕️ Nurse Dashboard</h1>
    <p class="text-muted">
        Welcome, {{ auth()->user()->name }}! You are logged in as 
        <strong>{{ auth()->user()->role }}</strong>.
    </p>

    <!-- Today's Appointments Summary -->
    <div class="my-3">
        <h5>📅 You have <strong id="todayAppointmentsCount">{{ $todayAppointments->count() }}</strong> appointment(s) today</h5>
    </div>

    <!-- Today's Appointments Table -->
    <div class="card shadow-sm rounded-4 mt-3">
        <div class="card-body">
            <h4 class="card-title mb-3">📋 Today's Appointments</h4>

            <div id="todayAppointmentsTableWrapper">
                @if ($todayAppointments->count() > 0)
                    <table class="table table-hover align-middle" id="todayAppointmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Patient Name</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->user->name ?? 'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('h:i A') }}</td>
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
                    <p class="text-muted text-center my-3">No appointments scheduled for today.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- All Upcoming Appointments -->
    <div class="card shadow-sm rounded-4 mt-4">
        <div class="card-body">
            <h4 class="card-title mb-3">📋 All Upcoming Appointments</h4>

            <div>
                @if ($upcomingAppointments->count() > 0)
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Patient Name</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcomingAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->user->name ?? 'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($appointment->status === 'pending') bg-warning
                                            @elseif($appointment->status === 'approved') bg-success
                                            @elseif($appointment->status === 'rescheduled') bg-info
                                            @elseif($appointment->status === 'declined') bg-danger
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
                    <p class="text-muted text-center my-3">No upcoming appointments.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📅 Manage All Appointments</h5>
                    <p class="card-text">View and manage all upcoming appointments.</p>
                    <a href="{{ route('nurse.appointments.index') }}" class="btn btn-success">Open</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📖 Student Records</h5>
                    <p class="card-text">View student medical history and profiles.</p>
                    <a href="{{ route('nurse.students.index') }}" class="btn btn-info">Open</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time JS for Today's Appointments -->
<script>
function fetchTodayAppointments() {
    fetch('{{ route("nurse.appointments.today-json") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('todayAppointmentsCount').innerText = data.count;
            let tableWrapper = document.getElementById('todayAppointmentsTableWrapper');
            if (data.count > 0) {
                let rows = data.appointments.map(app => {
                    let statusClass = 'bg-secondary';
                    if (app.status === 'pending') statusClass = 'bg-warning';
                    else if (app.status === 'approved') statusClass = 'bg-success';
                    let time = new Date(app.requested_datetime);
                    let formattedTime = time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    return `<tr>
                        <td>${app.user?.name ?? 'Unknown'}</td>
                        <td>${formattedTime}</td>
                        <td><span class="badge ${statusClass}">${app.status.charAt(0).toUpperCase() + app.status.slice(1)}</span></td>
                    </tr>`;
                }).join('');

                tableWrapper.innerHTML = `
                    <table class="table table-hover align-middle" id="todayAppointmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Patient Name</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                `;
            } else {
                tableWrapper.innerHTML = '<p class="text-muted text-center my-3">No appointments scheduled for today.</p>';
            }
        })
        .catch(error => console.error('Error fetching today\'s appointments:', error));
}

// Poll every 10 seconds
setInterval(fetchTodayAppointments, 10000);
</script>
@endsection

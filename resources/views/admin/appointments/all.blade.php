@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>All Appointments</h2>
    <div>
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#todayModal">
            <i class="bi bi-calendar-day"></i> Today's Appointments
        </button>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#weekModal">
            <i class="bi bi-calendar-week"></i> This Week's Appointments
        </button>
    </div>
</div>

{{-- All Appointments Table --}}
<div class="card shadow">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allAppointments as $appointment)
                <tr>
                    <td>{{ $appointment->user->name ?? 'Unknown' }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                    <td>{{ $appointment->time }}</td>
                    <td>{{ $appointment->status ?? 'Pending' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No appointments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
                    <span>{{ $appointment->user->name ?? 'Unknown' }}</span>
                    <span>{{ $appointment->time }}</span>
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
                    <span>{{ $appointment->user->name ?? 'Unknown' }}</span>
                    <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, M d') }} - {{ $appointment->time }}</span>
                </li>
                @endforeach
            </ul>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection

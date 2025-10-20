@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Emergency Report #{{ $emergency->id }}</h3>
    <div>
      <button class="btn btn-outline-secondary" onclick="window.print()">🖨 Print</button>
      <a href="{{ route('nurse.emergency.index') }}" class="btn btn-primary ms-2">Back</a>
    </div>
  </div>

  <div class="card shadow-sm p-3">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="fw-semibold">Student</div>
        <div>{{ $emergency->student->name ?? 'Unknown' }}</div>
      </div>
      <div class="col-md-6">
        <div class="fw-semibold">Reported At</div>
        <div>{{ $emergency->reported_at?->format('M d, Y h:i A') }}</div>
      </div>

      <div class="col-md-4">
        <div class="fw-semibold">Temperature</div>
        <div>{{ $emergency->temperature ?? '—' }}</div>
      </div>
      <div class="col-md-4">
        <div class="fw-semibold">Blood Pressure</div>
        <div>{{ $emergency->blood_pressure ?? '—' }}</div>
      </div>
      <div class="col-md-4">
        <div class="fw-semibold">Heart Rate</div>
        <div>{{ $emergency->heart_rate ?? '—' }}</div>
      </div>

      <div class="col-12">
        <div class="fw-semibold">Symptoms / Complaint</div>
        <div class="text-muted">{{ $emergency->symptoms }}</div>
      </div>

      <div class="col-12">
        <div class="fw-semibold">Diagnosis / Findings</div>
        <div class="text-muted">{{ $emergency->diagnosis }}</div>
      </div>

      <div class="col-12">
        <div class="fw-semibold">Treatment Given</div>
        <div class="text-muted">{{ $emergency->treatment }}</div>
      </div>

      @if($emergency->additional_notes)
      <div class="col-12">
        <div class="fw-semibold">Additional Notes</div>
        <div class="text-muted">{{ $emergency->additional_notes }}</div>
      </div>
      @endif

      <div class="col-md-6">
        <div class="fw-semibold">Guardian Notified</div>
        @if($emergency->guardian_notified)
          <span class="badge bg-success">Yes</span>
          <div class="small text-muted">{{ optional($emergency->guardian_notified_at)->format('M d, Y h:i A') }}</div>
        @else
          <span class="badge bg-secondary">No</span>
        @endif
      </div>

      <div class="col-md-6">
        <div class="fw-semibold">Recorded By</div>
        <div>{{ $emergency->nurse->name ?? 'Unknown' }}</div>
      </div>
    </div>
  </div>
</div>

<style>
@media print {
  .btn, a { display: none !important; }
  .card { border: none; box-shadow: none; }
}
</style>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">➕ New Emergency Report</h3>
    <a href="{{ route('nurse.emergency.index') }}" class="btn btn-outline-secondary">Back</a>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold mb-1">Please fix the following:</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('nurse.emergency.store') }}" class="card shadow-sm p-3">
    @csrf

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Student</label>
        {{-- Simple searchable via datalist (no external libs). If you want Select2 later we can add it. --}}
        <input class="form-control" list="students" id="studentPicker" placeholder="Type to search...">
        <datalist id="students">
          @foreach($students as $s)
            <option data-id="{{ $s->id }}" value="{{ $s->name }}"></option>
          @endforeach
        </datalist>
        <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id') }}">
        <div class="form-text">Start typing the student's name, then pick from suggestions.</div>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Reported At</label>
        <input type="datetime-local" name="reported_at" class="form-control"
               value="{{ old('reported_at', now()->format('Y-m-d\TH:i')) }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Temperature (°C)</label>
        <input type="text" name="temperature" class="form-control" value="{{ old('temperature') }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Blood Pressure (mmHg)</label>
        <input type="text" name="blood_pressure" class="form-control" value="{{ old('blood_pressure') }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Heart Rate (bpm)</label>
        <input type="text" name="heart_rate" class="form-control" value="{{ old('heart_rate') }}" required>
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Symptoms / Complaint</label>
        <textarea name="symptoms" class="form-control" rows="2" required>{{ old('symptoms') }}</textarea>
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Diagnosis / Findings</label>
        <textarea name="diagnosis" class="form-control" rows="2" required>{{ old('diagnosis') }}</textarea>
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Treatment Given</label>
        <textarea name="treatment" class="form-control" rows="2" required>{{ old('treatment') }}</textarea>
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Additional Notes (optional)</label>
        <textarea name="additional_notes" class="form-control" rows="2">{{ old('additional_notes') }}</textarea>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Guardian Notified?</label>
        <select name="guardian_notified" id="guardian_notified" class="form-select" required>
          <option value="0" @selected(old('guardian_notified')==='0')>No</option>
          <option value="1" @selected(old('guardian_notified')==='1')>Yes</option>
        </select>
      </div>

      <div class="col-md-6" id="guardian_time_wrap" style="display:none;">
        <label class="form-label fw-semibold">Guardian Notification Time</label>
        <input type="datetime-local" name="guardian_notified_at" id="guardian_notified_at"
               class="form-control" value="{{ old('guardian_notified_at') }}">
      </div>
    </div>

    <div class="text-end mt-4">
      <button class="btn btn-success">Save Report</button>
    </div>
  </form>
</div>

<script>
  // Simple datalist -> hidden id mapping
  (function() {
    const picker = document.getElementById('studentPicker');
    const hidden = document.getElementById('student_id');
    const options = document.getElementById('students').options;

    picker.addEventListener('change', () => {
      hidden.value = '';
      const val = picker.value;
      for (let i=0; i<options.length; i++) {
        if (options[i].value === val) {
          hidden.value = options[i].dataset.id;
          break;
        }
      }
    });

    // guardian time toggle
    const sel = document.getElementById('guardian_notified');
    const wrap = document.getElementById('guardian_time_wrap');
    const time = document.getElementById('guardian_notified_at');

    function toggleGuardianTime(){
      if (sel.value === '1') {
        wrap.style.display = '';
        if (!time.value) {
          time.value = new Date().toISOString().slice(0,16);
        }
      } else {
        wrap.style.display = 'none';
        time.value = '';
      }
    }

    sel.addEventListener('change', toggleGuardianTime);
    toggleGuardianTime();
  })();
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="mb-1">🚑 Emergency Case Reports</h3>
      <p class="text-muted mb-0">Track emergency cases. Use filters to narrow results.</p>
    </div>
    <a href="{{ route('nurse.emergency.create') }}" class="btn btn-primary">+ New Emergency Report</a>
  </div>

  {{-- Filters --}}
  <div class="mb-3">
    <div class="btn-group" role="group">
      <a href="{{ route('nurse.emergency.index', ['filter' => 'all']) }}"
         class="btn btn-outline-secondary {{ $filter === 'all' ? 'active' : '' }}">All</a>
      <a href="{{ route('nurse.emergency.index', ['filter' => 'today']) }}"
         class="btn btn-outline-secondary {{ $filter === 'today' ? 'active' : '' }}">Today</a>
      <a href="{{ route('nurse.emergency.index', ['filter' => 'week']) }}"
         class="btn btn-outline-secondary {{ $filter === 'week' ? 'active' : '' }}">This Week</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Reported At</th>
            <th>Student</th>
            <th>Vitals</th>
            <th>Guardian</th>
            <th>Recorded By</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        @forelse($records as $r)
          <tr>
            <td>{{ $r->reported_at->format('M d, Y h:i A') }}</td>
            <td>{{ $r->student->name ?? 'Unknown' }}</td>
            <td>
              <div class="small text-muted">
                Temp: {{ $r->temperature ?? '—' }} |
                BP: {{ $r->blood_pressure ?? '—' }} |
                HR: {{ $r->heart_rate ?? '—' }}
              </div>
            </td>
            <td>
              @if($r->guardian_notified)
                <span class="badge bg-success">Notified</span>
                <div class="small text-muted">{{ optional($r->guardian_notified_at)->format('M d, Y h:i A') }}</div>
              @else
                <span class="badge bg-secondary">Not Notified</span>
              @endif
            </td>
            <td>{{ $r->nurse->name ?? 'Unknown' }}</td>
            <td class="text-end">
              <a href="{{ route('nurse.emergency.show', $r) }}" class="btn btn-sm btn-outline-primary">View</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">No emergency records found.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $records->links() }}
    </div>
  </div>
</div>
@endsection

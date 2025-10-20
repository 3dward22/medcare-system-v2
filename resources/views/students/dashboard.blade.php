@extends('layouts.app')

@section('content')
<main class="pt-20 bg-gradient-to-br from-sky-50 via-white to-teal-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-6 py-8">
<!-- 🧭 Header -->
<!-- 🧭 Header -->
<div class="text-center mb-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-2 flex items-center justify-center">
        <span class="mr-2">🎓</span> Student Dashboard
    </h1>
    <p class="text-gray-600 text-base">
        Welcome back, 
        <span class="font-semibold text-blue-700">
            {{ auth()->user()->name }}
        </span>!
    </p>
    <div class="h-1 w-32 bg-gradient-to-r from-blue-500 to-teal-400 mx-auto mt-4 rounded-full"></div>
</div>

<!-- 📊 Appointment Summary Cards -->
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6 text-center hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-semibold">Pending</h3>
        <p class="text-3xl font-bold text-yellow-500 mt-2">
            {{ $appointments->where('status', 'pending')->count() }}
        </p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6 text-center hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-semibold">Approved</h3>
        <p class="text-3xl font-bold text-green-500 mt-2">
            {{ $appointments->where('status', 'approved')->count() }}
        </p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-6 text-center hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm uppercase font-semibold">Completed</h3>
        <p class="text-3xl font-bold text-blue-500 mt-2">
            {{ $appointments->where('status', 'completed')->count() }}
        </p>
    </div>
</div>

<!-- 📋 Appointment List -->
<div class="bg-white shadow-md rounded-2xl border border-gray-200 p-6">
    <!-- Header with button -->
    <div class="flex flex-col sm:flex-row items-center justify-between border-b pb-3 mb-4 text-center sm:text-left">
        <h2 class="text-lg font-semibold text-gray-700 flex items-center mb-3 sm:mb-0">
            <span class="mr-2">📅</span> Recent & Upcoming Appointments
        </h2>

        <a href="{{ route('student.appointments.index') }}"
           class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white text-sm font-semibold rounded-lg shadow hover:shadow-lg hover:from-blue-700 hover:to-teal-600 transition-all duration-200">
            <span class="mr-2">➕</span> Book Appointment
        </a>
    </div>

    @if ($appointments->count() > 0)
        <div class="space-y-4">
            @foreach ($appointments->sortByDesc('requested_datetime') as $appointment)
                <div class="border border-gray-100 rounded-xl p-4 hover:bg-blue-50 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-md font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('F d, Y - h:i A') }}
                            </h3>
                            <p class="text-sm mt-1 text-gray-500">
                                Status:
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                    @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status === 'approved') bg-green-100 text-green-800
                                    @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                    @elseif($appointment->status === 'declined') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </p>

                            @if($appointment->findings)
                                <p class="text-sm mt-2 text-gray-600 italic">
                                    “{{ Str::limit($appointment->findings, 60, '...') }}”
                                </p>
                            @else
                                <p class="text-sm mt-2 text-gray-400 italic">No findings yet.</p>
                            @endif
                        </div>

                        <button class="px-4 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-semibold rounded-lg shadow hover:from-blue-700 hover:to-teal-600 transition"
                            data-bs-toggle="modal"
                            data-bs-target="#appointmentModal{{ $appointment->id }}">
                            View
                        </button>
                    </div>
                </div>

                <!-- 🩺 Modal -->
                <div class="modal fade" id="appointmentModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content rounded-2xl border-0 shadow-lg">
                            <div class="modal-header bg-gradient-to-r from-blue-600 to-teal-500 text-white rounded-t-2xl">
                                <h5 class="modal-title font-semibold">🩺 Appointment Details</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-gray-700 text-sm space-y-2">
                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->requested_datetime)->format('F d, Y - h:i A') }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                @if($appointment->approved_by)
                                    <p><strong>Attending Nurse:</strong> {{ \App\Models\User::find($appointment->approved_by)->name ?? 'N/A' }}</p>
                                @endif
                                @if($appointment->admin_note)
                                    <p><strong>Diagnosis / Complaint:</strong> {{ $appointment->admin_note }}</p>
                                @endif
                                @if($appointment->findings)
                                    <p><strong>Findings & Treatment:</strong> {{ $appointment->findings }}</p>
                                @endif
                                @if($appointment->updated_at)
                                    <p><strong>Last Updated:</strong> {{ $appointment->updated_at->format('F d, Y h:i A') }}</p>
                                @endif
                            </div>
                            <div class="modal-footer bg-gray-50 rounded-b-2xl">
                                <button type="button"
                                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white rounded-lg shadow hover:from-blue-700 hover:to-teal-600 transition"
                                    data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 text-gray-500 text-sm">No upcoming appointments yet.</div>
    @endif
</div>


        
    </div>
</main>
@endsection

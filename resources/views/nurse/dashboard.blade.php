@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">👩‍⚕️ Nurse Dashboard</h1>
    <p class="text-muted">Welcome, {{ auth()->user()->name }}! You are logged in as <strong>{{ auth()->user()->role }}</strong>.</p>

    <div class="row mt-4">
        <!-- Medicines -->
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">💊 Medicines</h5>
                    <p class="card-text">View and manage medicine inventory.</p>
                    <a href="{{ route('medicines.index') }}" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>

        <!-- Appointments -->
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📅 Appointments</h5>
                    <p class="card-text">Approve, reschedule, or cancel student appointments.</p>
                    <a href="{{ route('appointments.index') }}" class="btn btn-success">View</a>
                </div>
            </div>
        </div>

        <!-- Student Records -->
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📖 Student Records</h5>
                    <p class="card-text">Access student medical history and profiles.</p>
<a href="{{ route('student.dashboard') }}" class="btn btn-info">Open</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Chat -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">💬 Messages</h5>
                    <p class="card-text">Respond to student inquiries and provide guidance.</p>
                    <a href="{{ route('chat.index') }}" class="btn btn-warning">Go to Chat</a>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">🔔 Notifications</h5>
                    <p class="card-text">Stay updated on new appointments and medicine alerts.</p>
                    <a href="{{ route('notifications.index') ?? '#' }}" class="btn btn-secondary">View</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

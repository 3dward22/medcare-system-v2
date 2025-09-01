@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🛠️ Admin Dashboard</h1>
    <p class="text-muted">Welcome, {{ auth()->user()->name }}! You are logged in as <strong>{{ auth()->user()->role }}</strong>.</p>

    <!-- Top cards (like Nurse dashboard) -->
    <div class="row mt-4">
        <!-- Medicines -->
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">💊 Medicines</h5>
                    <p class="card-text">View and manage medicine inventory.</p>
                    <a href="{{ route('nurse.medicines.index') }}" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>

        <!-- Appointments -->
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📅 Appointments</h5>
                    <p class="card-text">Monitor and manage student appointments.</p>
                    <a href="{{ route('nurse.appointments.index') }}" class="btn btn-success">View</a>
                </div>
            </div>
        </div>

        <!-- Student Records -->
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">📖 Student Records</h5>
                    <p class="card-text">Access and manage student medical history.</p>
                    <a href="{{ route('nurse.students.index') }}" class="btn btn-info">Open</a>
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
                    <p class="card-text">Communicate with students and nurses.</p>
                    <a href="{{ route('nurse.chat.students') }}" class="btn btn-warning">Go to Chat</a>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h5 class="card-title">🔔 Notifications</h5>
                    <p class="card-text">Stay updated on system alerts and activities.</p>
                    <a href="{{ route('notifications.index') ?? '#' }}" class="btn btn-secondary">View</a>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- Users Management Table -->
    <h3>👥 Users Management</h3>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(App\Models\User::all() as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

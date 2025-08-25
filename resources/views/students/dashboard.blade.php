@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="display-5 fw-bold text-dark mb-2">
                        Welcome back, {{ auth()->user()->name }}! 
                        <span class="wave">👋</span>
                    </h1>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-gradient-primary px-3 py-2 rounded-pill">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                        <span class="text-muted ms-3">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                <div class="text-end d-none d-md-block">
                    <div class="text-muted small">Good {{ now()->format('A') === 'AM' ? 'morning' : 'afternoon' }}!</div>
                    <div class="h5 mb-0 text-primary">{{ now()->format('g:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="row g-4">
        <!-- Appointments Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body p-4 text-center">
                    <div class="feature-icon bg-gradient-primary text-white rounded-circle mx-auto mb-4">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 class="card-title fw-bold mb-3">Appointments</h4>
                    <p class="card-text text-muted mb-4">
                        Schedule, view, and manage all your upcoming health appointments with ease.
                    </p>
                    <a href="{{ route('appointments.index') }}" 
                       class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">
                        <i class="fas fa-arrow-right me-2"></i>
                        Manage Appointments
                    </a>
                </div>
                <div class="card-footer bg-light border-0 text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Next appointment in 2 days
                    </small>
                </div>
            </div>
        </div>

        <!-- Chat Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body p-4 text-center">
                    <div class="feature-icon bg-gradient-warning text-white rounded-circle mx-auto mb-4">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4 class="card-title fw-bold mb-3">Chat Support</h4>
                    <p class="card-text text-muted mb-4">
                        Get instant medical advice and support from our qualified nursing staff.
                    </p>
                    <a href="{{ route('chat.index') }}" 
                       class="btn btn-warning btn-lg px-4 rounded-pill shadow-sm">
                        <i class="fas fa-comment-medical me-2"></i>
                        Start Conversation
                    </a>
                </div>
                <div class="card-footer bg-light border-0 text-center py-3">
                    <small class="text-muted">
                        <span class="badge bg-success rounded-pill me-1"></span>
                        Nurse available now
                    </small>
                </div>
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body p-4 text-center">
                    <div class="feature-icon bg-gradient-info text-white rounded-circle mx-auto mb-4">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h4 class="card-title fw-bold mb-3">Notifications</h4>
                    <p class="card-text text-muted mb-4">
                        Stay informed with important clinic updates, reminders, and health alerts.
                    </p>
                    <a href="{{ route('notifications.index') ?? '#' }}" 
                       class="btn btn-info btn-lg px-4 rounded-pill shadow-sm">
                        <i class="fas fa-eye me-2"></i>
                        View Updates
                    </a>
                </div>
                <div class="card-footer bg-light border-0 text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-exclamation-circle text-warning me-1"></i>
                        3 new notifications
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column">
                                <span class="h2 fw-bold text-primary mb-1">wa sa</span>
                                <span class="text-muted small">Total Appointments</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column">
                                <span class="h2 fw-bold text-success mb-1">wa pa pud</span>
                                <span class="text-muted small">Completed</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column">
                                <span class="h2 fw-bold text-warning mb-1">labaw</span>
                                <span class="text-muted small">Upcoming</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex flex-column">
                                <span class="h2 fw-bold text-info mb-1">shet nalay kuwang</span>
                                <span class="text-muted small">Chat Messages</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Animations and Styles */
.wave {
    animation: wave 2s infinite;
    display: inline-block;
}

@keyframes wave {
    0%, 20%, 60%, 100% { transform: rotate(0deg); }
    10% { transform: rotate(14deg); }
    30% { transform: rotate(-8deg); }
    40% { transform: rotate(14deg); }
    50% { transform: rotate(-4deg); }
    70% { transform: rotate(10deg); }
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.feature-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.card-footer {
    border-radius: 0 0 1rem 1rem;
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 1.75rem;
    }
    
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection
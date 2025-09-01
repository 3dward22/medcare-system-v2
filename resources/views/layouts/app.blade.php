<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Med System') }}</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- Laravel Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php
    $userRole = strtolower(auth()->user()->role ?? '');

    $dashboardRoute = match($userRole) {
        'admin'   => route('dashboard'),
        'nurse'   => route('nurse.dashboard'),
        'student' => route('student.dashboard'),
        default   => url('/'),
    };

    $medicinesRoute = match($userRole) {
        'admin' => route('admin.medicines.index'),
        'nurse' => route('medicines.index'),
        'student' => route('student.medicine'),
        default => null, // safer than '#'
    };

    $appointmentsRoute = match($userRole) {
        'nurse'   => route('nurse.appointments.index'),
        'student' => route('student.appointments.index'),
        default   => null,
    };
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ $dashboardRoute }}">Medcare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ $dashboardRoute }}">Dashboard</a>
                </li>

                @if(auth()->user()->role === 'nurse')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('nurse.medicines.index') }}">Medicines</a>
    </li>
@elseif(auth()->user()->role === 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.medicines.index') }}">Medicines</a>
    </li>
@endif

                @if($appointmentsRoute)
                <li class="nav-item">
                    <a class="nav-link" href="{{ $appointmentsRoute }}">Appointments</a>
                </li>
                @endif

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link p-0">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>



{{-- Main Content --}}
<main class="py-4 container">
    @yield('content')
</main>

{{-- jQuery (must come before medicines.js) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Toastr JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- Page-specific scripts --}}
@stack('scripts')
<script src="{{ asset('js/medicines.js') }}"></script>

<script>
    window.flashMessages = {
        success: "{{ session('success') }}",
        error: "{{ session('error') }}",
        warning: "{{ session('warning') }}",
        info: "{{ session('info') }}"
    };

    if (window.flashMessages.success) toastr.success(window.flashMessages.success);
    if (window.flashMessages.error) toastr.error(window.flashMessages.error);
    if (window.flashMessages.warning) toastr.warning(window.flashMessages.warning);
    if (window.flashMessages.info) toastr.info(window.flashMessages.info);
</script>

</body>
</html>

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📖 Student Records</h1>

    @if($students->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">View Appointments</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No student records found.</p>
    @endif
</div>
@endsection

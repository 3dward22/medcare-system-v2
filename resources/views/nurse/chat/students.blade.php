@extends('layouts.app')

@section('content')
<div class="container">
    <h3>👩‍⚕️ Nurse Chat - Students</h3>

    <div class="list-group mt-4">
        @foreach($students as $student)
            <a href="{{ route('nurse.chat.with_student', $student->id) }}" class="list-group-item list-group-item-action">
                {{ $student->name }} ({{ $student->email }})
            </a>
        @endforeach
    </div>
</div>
@endsection

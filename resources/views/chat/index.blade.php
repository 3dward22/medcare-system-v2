@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">💬 Chat Bot</h1>

    <div class="card">
        <div class="card-body" style="height:400px; overflow-y:scroll;">
            @forelse($messages as $msg)
                @if($msg['sender'] == 'user')
                    <p><strong>You:</strong> {{ $msg['text'] }}</p>
                @else
                    <p><strong>Bot:</strong> {{ $msg['text'] }}</p>
                @endif
            @empty
                <p>No messages yet. Say hi!</p>
            @endforelse
        </div>
    </div>

    <form action="{{ route('chat.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
            <button class="btn btn-primary" type="submit">Send</button>
        </div>
    </form>
</div>
@endsection

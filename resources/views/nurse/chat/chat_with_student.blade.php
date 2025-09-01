@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Chat with {{ $student->name }}</h3>

    <div id="chat-box" class="border rounded p-3 mb-3" style="height: 400px; overflow-y: auto; background: #f8f9fa;">
        @foreach($messages as $msg)
            @php
                $isMine = $msg->sender_id == auth()->id();
            @endphp

            <div class="mb-2 d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="p-2 rounded {{ $isMine ? 'bg-primary text-white' : 'bg-secondary text-dark' }}" style="max-width: 60%;">
                    <strong>{{ $msg->sender->name }}</strong>
                    <p class="mb-1">{{ $msg->message }}</p>
                    <small class="text-muted">{{ $msg->created_at->format('H:i, d M') }}</small>
                </div>
            </div>
        @endforeach
    </div>

    <form id="chat-form">
        @csrf
        <div class="input-group">
            <input type="text" id="message" class="form-control" placeholder="Type a message..." required>
            <button class="btn btn-primary" type="submit">Send</button>
        </div>
    </form>

    <a href="{{ route('nurse.chat.students') }}" class="btn btn-secondary mt-3">Back to Students</a>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const studentId = parseInt("{{ $student->id }}", 10);

    // Auto-scroll to bottom
    chatBox.scrollTop = chatBox.scrollHeight;

    // Send message via AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = document.getElementById('message').value.trim();
        if(!message) return;

        fetch("{{ route('nurse.chat.send_message', $student->id) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ message })
        })
        .then(res => res.json())
        .then(data => {
            appendMessage('{{ auth()->user()->name }}', data.message, data.time, true);
            document.getElementById('message').value = '';
        })
        .catch(err => console.error(err));
    });

    // Fetch new messages every 2 seconds
    setInterval(fetchMessages, 2000);

    function fetchMessages() {
        fetch(`/nurse/chat/fetch/${studentId}`)
        .then(res => res.json())
        .then(data => {
            chatBox.innerHTML = ''; // Clear existing messages
            data.forEach(msg => {
                appendMessage(msg.sender_name, msg.message, msg.time, msg.is_mine);
            });
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
        });
    }

    function appendMessage(sender, message, time, isMine) {
        const div = document.createElement('div');
        div.classList.add('mb-2', 'd-flex', isMine ? 'justify-content-end' : 'justify-content-start');

        const bubble = document.createElement('div');
        bubble.classList.add('p-2', 'rounded');
        bubble.style.maxWidth = '60%';
        bubble.style.background = isMine ? '#0d6efd' : '#e2e3e5';
        bubble.style.color = isMine ? 'white' : 'black';

        bubble.innerHTML = `<strong>${sender}</strong><p class="mb-1">${message}</p><small class="text-muted">${time}</small>`;
        div.appendChild(bubble);
        chatBox.appendChild(div);
    }
});
</script>
@endsection

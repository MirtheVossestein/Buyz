@extends('layouts.app')

@section('title', 'Gesprek')

@section('content')
    <h1>Gesprek met {{ $partner->first_name }} {{ $partner->last_name }}</h1>
    <h2>Advertentie:
        <a href="{{ route('ads.show', $advertentie->id) }}">
            {{ $advertentie->title }}
        </a>
    </h2>

    @if (auth()->id() === $advertentie->user_id)
        <form method="POST" action="{{ route('ads.updateStatus', $advertentie->id) }}" class="mb-4">
            @csrf
            @method('PATCH')

            <label for="buyer_id" class="block mb-1 font-semibold">Selecteer koper:</label>
            <select name="buyer_id" id="buyer_id" required class="border rounded p-1 mb-3 w-full max-w-xs">
                <option value="">-- Kies koper --</option>
                <option value="{{ $partner->id }}" {{ $advertentie->buyer_id === $partner->id ? 'selected' : '' }}>
                    {{ $partner->first_name }} {{ $partner->last_name }}
                </option>
            </select>

            <label for="status" class="block mb-1 font-semibold">Status:</label>
            <select name="status" onchange="this.form.submit()" class="border p-1 rounded w-full max-w-xs">
                <option value="te_koop" {{ $advertentie->status === 'te_koop' ? 'selected' : '' }}>Te koop</option>
                <option value="verkocht" {{ $advertentie->status === 'verkocht' ? 'selected' : '' }}>Verkocht</option>
                <option value="gereserveerd" {{ $advertentie->status === 'gereserveerd' ? 'selected' : '' }}>Gereserveerd
                </option>
            </select>

            <button type="submit" class="btn btn-primary mt-3">Status bijwerken</button>
        </form>
    @endif

    @if (auth()->id() === $advertentie->buyer_id &&
            $advertentie->status === 'verkocht' &&
            !\App\Models\Review::where('ad_id', $advertentie->id)->where('reviewer_id', auth()->id())->exists())
        <div class="review-invite border p-4 rounded mb-4 bg-yellow-50">
            <p>De verkoper heeft de status aangepast. Je kunt nu een review achterlaten over deze verkoper.</p>

            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="ad_id" value="{{ $advertentie->id }}">

                <label for="rating">Rating:</label>
                <select name="rating" id="rating" required class="block mb-2 border rounded p-1">
                    <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                    <option value="4">⭐️⭐️⭐️⭐️</option>
                    <option value="3">⭐️⭐️⭐️</option>
                    <option value="2">⭐️⭐️</option>
                    <option value="1">⭐️</option>
                </select>

                <label for="comment">Commentaar:</label>
                <textarea name="comment" id="comment" rows="4" class="block w-full border rounded p-2 mb-2"></textarea>

                <button type="submit" class="btn btn-primary">Review plaatsen</button>
            </form>
        </div>
    @endif

    <div id="chat-messages">
        @foreach ($conversation->messages as $message)
            <div class="{{ $message->sender_id === auth()->id() ? 'text-end' : 'text-start' }} group relative mb-3">
                <p>
                    <strong>
                        {{ $message->sender_id === auth()->id() ? 'Jij' : $message->sender->first_name . ' ' . $message->sender->last_name }}
                    </strong>: {{ $message->content }}
                </p>
                <small>{{ $message->created_at->diffForHumans() }}</small>

                @if ($message->sender_id === auth()->id())
                    <form method="POST" action="{{ route('messages.destroy', $message->id) }}"
                        onsubmit="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?')"
                        class="absolute top-0 right-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 opacity-50 mt-3 text-sm">Verwijderen</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>



    <form method="POST" action="{{ route('conversations.messages.store', $conversation->id) }}">
        @csrf
        <textarea name="content" rows="3" required class="w-full border p-2 mt-4" placeholder="Typ een bericht..."></textarea>
        <button type="submit" class="btn btn-primary mt-2">Verstuur</button>
    </form>

    @push('scripts')
        <script>
            const conversationId = "{{ $conversation->id }}";

            function fetchMessages() {
                fetch(`/conversations/${conversationId}/messages`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('chat-messages');
                        container.innerHTML = '';

                        data.forEach(msg => {
                            const wrapper = document.createElement('div');
                            wrapper.className = (msg.sender_id == {{ auth()->id() }}) ?
                                'text-end group relative mb-3' : 'text-start group relative mb-3';
                            wrapper.innerHTML = `
                        <p><strong>${msg.sender_id == {{ auth()->id() }} ? 'Jij' : msg.sender.first_name + ' ' + msg.sender.last_name}</strong>: ${msg.content}</p>
                        <small>${new Date(msg.created_at).toLocaleString()}</small>
                    `;
                            container.appendChild(wrapper);
                        });
                    });
            }

            setInterval(fetchMessages, 5000);
        </script>
    @endpush
@endsection

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

            <button type="submit"
                class="mt-4 bg-[#00A9A3] text-white px-4 py-2 rounded  hover:bg-[#019A95] transition ">Status
                bijwerken</button>
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

                <button type="submit"
                    class="bg-[#00A9A3] text-white px-4 py-2 rounded w-full  hover:bg-[#019A95] transition">Review
                    plaatsen</button>
            </form>
        </div>
    @endif

    <div id="chat-messages" class="p-4 bg-gray-100 rounded-xl overflow-y-auto">
        @foreach ($conversation->messages as $message)
            <div class="mb-4 flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div
                    class="max-w-xs md:max-w-md px-4 py-2 rounded-2xl shadow-md relative
        {{ $message->sender_id === auth()->id()
            ? 'bg-[#f5cc6e] text-right rounded-br-none'
            : 'bg-white text-left rounded-bl-none border' }}">

                    <p class="text-sm text-gray-800">
                        <strong>{{ $message->sender_id === auth()->id() ? 'Jij' : $message->sender->first_name }}</strong><br>
                        {{ $message->content }}
                    </p>
                    <small class="text-gray-500 text-xs block mt-1">{{ $message->created_at->diffForHumans() }}</small>

                    @if ($message->sender_id === auth()->id())
                        <form method="POST" action="{{ route('messages.destroy', $message->id) }}"
                            onsubmit="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?')"
                            class="absolute top-1 right-2 text-xs text-red-500 hover:text-red-700">
                            @csrf
                            @method('DELETE')
                            <button type="submit">✕</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>



    <form method="POST" action="{{ route('conversations.messages.store', $conversation->id) }}">
        @csrf
        <textarea name="content" rows="3" required class="w-full border rounded-xl p-2 mt-4" placeholder="Typ een bericht..."></textarea>
        <button type="submit" class="bg-[#00A9A3] hover:bg-[#019A95] text-white mb-4 px-4 py-2 rounded">Verstuur</button>
    </form>

   @push('scripts')
<script>
    const conversationId = "{{ $conversation->id }}";
    const authId = {{ auth()->id() }};

    function fetchMessages() {
        fetch(`/conversations/${conversationId}/messages`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('chat-messages');
                container.innerHTML = '';

                data.forEach(msg => {
                    const isSender = msg.sender_id === authId;

                    const wrapper = document.createElement('div');
                    wrapper.className = 'mb-4 flex ' + (isSender ? 'justify-end' : 'justify-start');

                    const bubble = document.createElement('div');
                    bubble.className =
                        'max-w-xs md:max-w-md px-4 py-2 rounded-2xl shadow-md relative ' +
                        (isSender
                            ? 'bg-[#f5cc6e] text-right rounded-br-none'
                            : 'bg-white text-left rounded-bl-none border');

                    bubble.innerHTML = `
                        <p class="text-sm text-gray-800">
                            <strong>${isSender ? 'Jij' : msg.sender.first_name}</strong><br>
                            ${msg.content}
                        </p>
                        <small class="text-gray-500 text-xs block mt-1">${new Date(msg.created_at).toLocaleString()}</small>
                    `;

                    if (isSender) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/messages/${msg.id}`;
                        form.onsubmit = () => confirm('Weet je zeker dat je dit bericht wilt verwijderen?');
                        form.className = 'absolute top-1 right-2 text-xs text-red-500 hover:text-red-700';

                        form.innerHTML = `
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit">✕</button>
                        `;

                        bubble.appendChild(form);
                    }

                    wrapper.appendChild(bubble);
                    container.appendChild(wrapper);
                });
            });
    }

    setInterval(fetchMessages, 5000);
</script>
@endpush

@endsection

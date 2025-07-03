@extends('layouts.app')

@section('content')
    <h1>Admin Dashboard</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button"
                role="tab" aria-controls="admin" aria-selected="true">Admin</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button"
                role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button"
                role="tab" aria-controls="stats" aria-selected="false">Statistieken</button>
        </li>
    </ul>

    {{-- Tabs --}}
    <div class="tab-content" id="dashboardTabsContent">
        <div class="tab-pane fade show active" id="admin" role="tabpanel" aria-labelledby="admin-tab">
            {{-- Admin tab --}}
            <h2>Actieve Admins</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin->email }}</td>
                            <td>
                                @if ($admin->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.remove-admin', $admin->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit">Verwijder admin</button>
                                    </form>
                                @else
                                    <em>Is al admin (jijzelf)</em>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h2>Overige Gebruikers</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.make-admin', $user->id) }}"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit">Maak admin</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Reviews tab --}}
        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
            <h2>Geschreven Reviews</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Review</th>
                        <th>Geschreven door</th>
                        <th>Review voor</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            <td>{{ Str::limit($review->comment, 100) }}</td>
                            <td>{{ $review->reviewer->first_name }}
                                {{ $review->reviewer->last_name }}<br>{{ $review->reviewer->email }}</td>
                            <td>{{ $review->reviewee->first_name }}
                                {{ $review->reviewee->last_name }}<br>{{ $review->reviewee->email }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editReviewModal-{{ $review->id }}">
                                    Bewerk
                                </button>

                                <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Weet je zeker dat je deze review wilt verwijderen?')">Verwijder</button>
                                </form>
                            </td>
                        </tr>


                        <div class="modal fade" id="editReviewModal-{{ $review->id }}" tabindex="-1"
                            aria-labelledby="editReviewModalLabel-{{ $review->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editReviewModalLabel-{{ $review->id }}">
                                                Review bewerken</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Sluiten"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="rating-{{ $review->id }}" class="form-label">Beoordeling
                                                    (1-5)
                                                </label>
                                                <input type="number" name="rating" class="form-control"
                                                    id="rating-{{ $review->id }}"
                                                    value="{{ old('rating', $review->rating) }}" min="1"
                                                    max="5" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="comment-{{ $review->id }}"
                                                    class="form-label">Opmerking</label>
                                                <textarea name="comment" class="form-control" id="comment-{{ $review->id }}" rows="3">{{ old('comment', $review->comment) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuleren</button>
                                            <button type="submit" class="btn btn-primary">Opslaan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Stats tab --}}
        <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
            <h2>Statistieken</h2>

            <div class="row">
                <div class="col-md-6">
                    <canvas id="usersChart"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="adsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const adsCtx = document.getElementById('adsChart').getContext('2d');

    const monthLabels = ['Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];

    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Nieuwe Gebruikers per Maand',
                data: @json($usersData),
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
    });

    new Chart(adsCtx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Advertenties per Maand',
                data: @json($adsData),
                backgroundColor: 'rgba(255, 99, 132, 0.6)'
            }]
        },
    });
</script>
@endpush

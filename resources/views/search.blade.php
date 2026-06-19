@extends('layouts.app')

@section('title', 'Kam na dovolenku?')

@section('content')
<section class="hero">
    <div>
        <h1>Kam na dovolenku?</h1>
        <p>Vyplňte preferencie a aplikácia odporučí destinácie, vysvetlí dôvody výberu a pripraví detailné údaje pre plánovanie cesty.</p>
    </div>
</section>

<section class="workspace">
    <form method="post" action="{{ rtrim(config('app.url'), '/') }}/" class="panel search-panel">
        @csrf
        <div class="panel-head">
            <h2>Vyhľadávací formulár</h2>
            <button class="icon-button primary" type="submit" title="Vyhľadať">
                <i data-lucide="search"></i><span>Vyhľadať</span>
            </button>
        </div>

        <div class="form-grid">
            <fieldset>
                <legend>Kedy chcete cestovať</legend>
                <label class="radio-row">
                    <input type="radio" name="travel_mode" value="month" @checked($search['travel_mode'] === 'month')>
                    <span>Mesiac</span>
                </label>
                <select name="month">
                    @foreach ($months as $num => $name)
                        <option value="{{ $num }}" @selected($search['month'] === $num)>{{ $name }}</option>
                    @endforeach
                </select>
                <label class="radio-row">
                    <input type="radio" name="travel_mode" value="range" @checked($search['travel_mode'] === 'range')>
                    <span>Dátumový rozsah</span>
                </label>
                <div class="date-pair">
                    <input type="date" name="date_from" value="{{ $search['date_from'] }}">
                    <input type="date" name="date_to" value="{{ $search['date_to'] }}">
                </div>
            </fieldset>

            <fieldset>
                <legend>Ako dlho</legend>
                <label>Počet dní</label>
                <input type="number" min="1" max="90" name="days" value="{{ $search['days'] }}">
            </fieldset>

            <fieldset>
                <legend>Čo hľadáte</legend>
                @foreach ($types as $value => $label)
                    <label class="check-row">
                        <input type="checkbox" name="types[]" value="{{ $value }}" @checked(in_array($value, $search['types'], true))>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </fieldset>

            <fieldset>
                <legend>Preferovaná teplota</legend>
                @foreach ($temperatures as $value => $label)
                    <label class="radio-row">
                        <input type="radio" name="temperature" value="{{ $value }}" @checked($search['temperature'] === $value)>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </fieldset>

            <fieldset>
                <legend>Vzdialenosť</legend>
                @foreach ($distances as $value => $label)
                    <label class="radio-row">
                        <input type="radio" name="distance" value="{{ $value }}" @checked($search['distance'] === $value)>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </fieldset>
        </div>
    </form>

    <section class="panel results-panel">
        <div class="panel-head">
            <h2>Odporúčané destinácie</h2>
            @if ($hasSearch)
                <span class="muted">{{ $results->count() }} výsledkov pre {{ $selectedMonthLabel }}</span>
            @endif
        </div>

        @if (!$hasSearch)
            <div class="empty-state">Zadajte preferencie a spustite vyhľadávanie.</div>
        @elseif ($results->isEmpty())
            <div class="empty-state">Nenašli sa destinácie. Skúste povoliť väčšiu vzdialenosť alebo inú teplotu.</div>
        @else
            <form method="get" action="{{ rtrim(config('app.url'), '/') }}/compare.php" class="result-list">
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                @foreach ($selectedMonths as $month)
                    <input type="hidden" name="months[]" value="{{ $month }}">
                @endforeach
                @foreach ($results as $destination)
                    @php($climate = $destination->getRelation('selectedClimate'))
                    <article class="destination-card">
                        <label class="compare-pick" title="Pridať do porovnania">
                            <input type="checkbox" name="ids[]" value="{{ $destination->id }}">
                            <span></span>
                        </label>
                        <div class="card-media" style="background-image: url('{{ $destination->image_url }}')"></div>
                        <div class="card-body">
                            <h3>{{ $destination->name }}</h3>
                            <p>{{ $destination->country_name }} · {{ number_format($destination->flight_hours, 1, ',', ' ') }} h letu · {{ $climate->avg_min }}-{{ $climate->avg_max }} °C</p>
                            <ul>
                                @foreach ($destination->reasons as $reason)
                                    <li>{{ $reason }}</li>
                                @endforeach
                            </ul>
                            <div class="card-actions">
                                <a class="button ghost" href="{{ rtrim(config('app.url'), '/') }}/destination.php?id={{ $destination->id }}@foreach ($selectedMonths as $month)&amp;months[]={{ $month }}@endforeach">Detail</a>
                            </div>
                        </div>
                    </article>
                @endforeach
                <button class="compare-submit icon-button" type="submit">
                    <i data-lucide="columns-3"></i><span>Porovnať vybrané</span>
                </button>
            </form>
        @endif
    </section>
</section>
@endsection

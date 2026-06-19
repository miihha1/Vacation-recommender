@extends('layouts.app')

@section('title', $destination->name)

@section('content')
<section class="detail-hero" style="background-image: linear-gradient(90deg, rgba(15, 23, 42, .72), rgba(15, 23, 42, .18)), url('{{ $destination->image_url }}')">
    <div>
        <a class="backlink" href="{{ rtrim(config('app.url'), '/') }}/">← späť na vyhľadávanie</a>
        <h1>{{ $destination->name }}</h1>
        <p>{{ $destination->country_name }} · {{ $destination->summary }}</p>
    </div>
</section>

<section class="detail-grid">
    <article class="panel">
        <h2>Počasie v zvolenom mesiaci</h2>
        @php($avgTemp = round(($selectedClimate->avg_min + $selectedClimate->avg_max) / 2, 1))
        <p class="muted">Historické hodnoty pre mesiac {{ $selectedMonthLabel }}.</p>
        <div class="metric-row">
            <div><strong>{{ $avgTemp }} °C</strong><span>priemerná teplota</span></div>
            <div><strong>{{ $selectedClimate->avg_min }} °C</strong><span>priemerné minimum</span></div>
            <div><strong>{{ $selectedClimate->avg_max }} °C</strong><span>priemerné maximum</span></div>
        </div>
        <p class="muted">
            Najbližšie letisko: {{ $airport?->name ?? 'nezistené' }}
            @if ($airport)
                · {{ round($airport->distance_km) }} km
            @endif
        </p>
        @if ($forecast)
            <p class="muted">Aktuálna predpoveď: {{ data_get($forecast, 'current.temperature_2m') }} °C, zrážky {{ data_get($forecast, 'current.precipitation') }} mm.</p>
        @else
            <p class="muted">Aktuálna predpoveď nie je dostupná. Najbližšie letisko: {{ $airport?->name ?? 'nezistené' }}.</p>
        @endif
    </article>

    <article class="panel">
        <h2>Štát a základné informácie</h2>
        <div class="country-line">
            <img src="https://www.geonames.org/flags/x/{{ strtolower($destination->country_code) }}.gif" alt="Vlajka">
            <div>
                <strong>{{ $destination->country_name }}</strong>
                <span>Hlavné mesto: {{ $country?->capital ?? $destination->capital }}</span>
            </div>
        </div>
        <dl class="info-list">
            <div><dt>Mena</dt><dd>{{ $destination->currency_code }} · {{ $destination->currency_name }}</dd></div>
            @if ($rate !== null)
                <div><dt>Kurz</dt><dd>1 EUR ≈ {{ number_format($rate, 2, ',', ' ') }} {{ $destination->currency_code }}</dd></div>
            @endif
            <div><dt>Let z Viedne</dt><dd>{{ number_format($destination->flight_hours, 1, ',', ' ') }} h</dd></div>
        </dl>
    </article>

    <article class="panel wide">
        <h2>Prečo práve teraz</h2>
        <p>{{ $recommendation }}</p>
    </article>
</section>
@endsection

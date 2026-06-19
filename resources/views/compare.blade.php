@extends('layouts.app')

@section('title', 'Porovnanie destinácií')

@section('content')
<section class="panel">
    <div class="panel-head">
        <h1>Porovnanie destinácií</h1>
        <a class="button ghost" href="{{ rtrim(config('app.url'), '/') }}/">Vybrať z výsledkov</a>
    </div>

    @if ($destinations->count() < 2)
        <div class="empty-state">Vyberte aspoň dve destinácie vo výsledkoch vyhľadávania a kliknite na porovnanie.</div>
    @else
        <p class="muted">Zvolený mesiac: {{ $selectedMonthLabel }}</p>
        <div class="compare-table-wrap">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Parameter</th>
                        @foreach ($destinations as $destination)
                            <th>{{ $destination->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Krajina</td>@foreach ($destinations as $d)<td>{{ $d->country_name }}</td>@endforeach</tr>
                    <tr><td>Typ dovolenky</td>@foreach ($destinations as $d)<td>{{ implode(', ', \App\Services\VacationCatalog::typeLabels($d->types)) }}</td>@endforeach</tr>
                    <tr><td>Priemerné počasie</td>@foreach ($destinations as $d)@php($c = $d->getRelation('selectedClimate'))<td>{{ $c->avg_min }}-{{ $c->avg_max }} °C</td>@endforeach</tr>
                    <tr><td>Mena</td>@foreach ($destinations as $d)<td>{{ $d->currency_code }} · {{ $d->currency_name }}</td>@endforeach</tr>
                    <tr><td>Dĺžka letu z Viedne</td>@foreach ($destinations as $d)<td>{{ number_format($d->flight_hours, 1, ',', ' ') }} h</td>@endforeach</tr>
                    <tr><td>Odporúčanie</td>@foreach ($destinations as $d)@php($c = $d->getRelation('selectedClimate'))<td>{{ $recommendations->text($d, $c) }}</td>@endforeach</tr>
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection

@extends('layouts.app')

@section('title', 'Štatistiky')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<section class="stats-grid">
    <article class="panel stat-card">
        <span>Návštevnosť portálu</span>
        <strong>{{ $total }}</strong>
        <p>unikátne za posledných 60 minút: {{ $unique }}</p>
    </article>
    <article class="panel">
        <h2>Návštevnosť podľa dennej doby</h2>
        <canvas id="timeChart" height="170" data-series='@json($timeRows)'></canvas>
    </article>
    <article class="panel wide">
        <h2>Čo ľudia hľadajú</h2>
        <div class="table-wrap">
            <table class="data-table" data-sortable>
                <thead><tr><th>Názov destinácie</th><th>Štát</th><th>Počet vyhľadaní</th></tr></thead>
                <tbody>
                    @foreach ($searched as $row)
                        <tr><td>{{ $row->name }}</td><td>{{ $row->country_name }}</td><td>{{ $row->cnt }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
    <article class="panel">
        <h2>Preferencie návštevníkov</h2>
        <canvas id="prefChart" height="180" data-series='@json($prefs)'></canvas>
    </article>
    <article class="panel">
        <h2>Typy dovoleniek</h2>
        <canvas id="typeChart" height="180" data-series='@json($typeCounts)'></canvas>
    </article>
</section>
@endsection

@extends('admin.include.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMdfh7zbeJvWqFb7wPp4Oftr9+8eX8jNynjLfV3" crossorigin="anonymous">
<div class="flex items-center flex-wrap justify-between gap20 mb-27" id="snackbar">
    <h3>Avis des Événements</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href=""><div class="text-tiny">Avis des Événements</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Liste des Avis</div>
        </li>
    </ul>
</div>

@php
use Carbon\Carbon;
@endphp

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <div class="show">
                <div class="text-tiny">Affichage</div>
                <div class="select">
                    <form action="{{ route('avis') }}" method="GET" id="entriesForm">
                        <select name="perPage" id="perPage" onchange="document.getElementById('entriesForm').submit();">
                            <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="30" {{ request('perPage') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
                <div class="text-tiny">entrées</div>
            </div>
            <div class="flex gap10">
                <div class="select w200">
                    <select id="filterOption" onchange="applyFilter()">
                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Défaut</option>
                        <option value="evenement" {{ request('filter') == 'evenement' ? 'selected' : '' }}>Par événement</option>
                        <option value="participant" {{ request('filter') == 'participant' ? 'selected' : '' }}>Par participant</option>
                    </select>
                </div>
                <button class="tf-button style-1 w128" onclick="applyFilter()">Filtrer</button>
            </div>
        </div>
        <a class="tf-button style-1 w208" href=""><i class="icon-refresh-ccw"></i>Actualiser</a>
    </div>

    <div class="wg-table table-all-category">
        <ul class="table-title flex gap20 mb-14">
            <li>
                <div class="body-title">Participant</div>
            </li>
            <li>
                <div class="body-title">Événement</div>
            </li>
            <li>
                <div class="body-title">Avis</div>
            </li>
            <li>
                <div class="body-title">Fait le</div>
            </li>
            <li>
                <div class="body-title">Statut</div>
            </li>
        </ul>

        <ul>
            @forelse($events as $event)
            @foreach($event->avis as $avis)
            <li class="product-item gap14">

                <div class="image no-bg">
                    @if ($avis->user->profile && $avis->user->profile !== 'default_image_url')
                    <img src="{{ asset($avis->user->profile) }}" class="w-10 h-12 rounded-full object-contain" alt="{{ $avis->user->name }}">
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                    @endif
                </div>
                <div class="flex items-center justify-between gap20 flex-grow">
                    <div class="name">
                        <a href="" class="body-title-2">{{ $avis->user->name }}</a>
                    </div>
                    <div class="body-text">{{ $event->nom }} ({{ $event->type }})</div>
                    <div class="body-text">
                        <div>{{ $avis->comment}}</div>
                        <div class=" body-text">
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                <span class="icon-star{{ $i <= $avis->note ? '' : '-o' }}"></span>
                                @endfor
                                ({{$avis->note}}/5)
                            </div>
                        </div>
                    </div>
                    <div class="body-text">{{ Carbon::parse($avis->created_at)->format('d M Y') }}</div>
                    <div>
                        @if($avis->status == 'bloqué')
                        <div class="block-pending">{{ ucfirst($avis->status) }}</div>
                        @elseif($avis->status == 'actif')
                        <div class="block-published">{{ ucfirst($avis->status) }}</div>
                        @endif
                    </div>
                </div>
            </li>
            @endforeach
            @empty
            <li class="product-item gap14">
                <div class="flex items-center justify-center w-full">
                    <p>Aucun avis trouvé.</p>
                </div>
                @endforelse
        </ul>

    </div>
    <div class="divider"></div>
    <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Compteur : {{ $events->total() }} avis</div>
        <ul class="wg-pagination">
            {{ $events->links('vendor.pagination.custom') }}
        </ul>
    </div>
</div>

<style>

    .rating .icon-star, .rating .icon-star-o {
        color: #FFD700;
        font-size: 15px;
    }

</style>

<script>
    function applyFilter() {
        var filterOption = document.getElementById('filterOption').value;
        var url = "{{ route('avis') }}";

        if (filterOption !== 'all') {
            url += "?filter=" + filterOption;
        }

        window.location.href = url;
    }

    document.addEventListener('DOMContentLoaded', function() {
        var filterOption = "{{ $filter }}";
        if (filterOption !== 'all') {
            document.getElementById('filterOption').value = filterOption;
        }
    });
</script>

@endsection

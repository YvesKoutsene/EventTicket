@extends('admin.include.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMdfh7zbeJvWqFb7wPp4Oftr9+8eX8jNynjLfV3" crossorigin="anonymous">
<div class="flex items-center flex-wrap justify-between gap20 mb-27" id="snackbar">
    <h3>Evènements</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href=""><div class="text-tiny">Evènements</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Liste Evènement</div>
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
                    <form action="{{ route('event') }}" method="GET" id="entriesForm">
                        <select name="perPage" id="perPage" onchange="document.getElementById('entriesForm').submit();">
                            <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10
                            <option value="30" {{ request('perPage') == 30 ? 'selected' : '' }}>30
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100
                        </select>
                    </form>
                </div>
                <div class="text-tiny">entrées</div>
            </div>
            <div class="flex gap10">
                <div class="select w200">
                    <select id="filterOption" onchange="applyFilter()">
                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Defaut
                        <option value="category" {{ request('filter') == 'category' ? 'selected' : '' }}>Par catégorie
                        <option value="organizer" {{ request('filter') == 'organizer' ? 'selected' : '' }}>Par organisateur
                    </select>
                </div>
                <button class="tf-button style-1 w128" onclick="applyFilter()">Filtrer</button>
            </div>
            <form class="form-search ">

            </form>
        </div>
        <a class="tf-button style-1 w208" href=""><i class="icon-refresh-ccw"></i>Actualiser</a>
    </div>

    <div class="wg-table table-all-category">
        <ul class="table-title flex gap20 mb-14">
            <li>
                <div class="body-title">Evènement</div>
            </li>
            <li>
                <div class="body-title">Categorie</div>
            </li>
            <li>
                <div class="body-title">Organisé par</div>
            </li>
            <li>
                <div class="body-title">Lieu</div>
            </li>
            <li>
                <div class="body-title">Déroulement</div>
            </li>
            <li>
                <div class="body-title">Heure</div>
            </li>
            <li>
                <div class="body-title">Statut</div>
            </li>
            <li>
                <div class="body-title">Publié le</div>
            </li>
            <li>
                <div class="body-title">Actions</div>
            </li>
        </ul>

        <ul>
            @forelse($event as $ev)
            <li class="product-item gap14">
                <div class="image no-bg">
                    @if ($ev->image && $ev->image !== 'default_image_url')
                    <img src="{{ asset($ev->image) }}" class="w-10 h-12 rounded-full object-contain" alt="{{ $ev->nom }}">
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                    @endif
                </div>
                <div class="flex items-center justify-between gap20 flex-grow">
                    <div class="name">
                        <a href="{{ route('form-showEvent', ['id' => $ev->id]) }}" class="body-title-2">{{ $ev->nom }} ({{ $ev->type }} de {{$ev->place}})</a>
                    </div>
                    <div class="body-text">{{ $ev->categorie->nom }}</div>
                    <div class="body-text">{{ $ev->user->name }}</div>
                    <div class="body-text">{{ $ev->lieu }}</div>
                    <div class="body-text">{{ Carbon::parse($ev->dateDebut)->format('d M Y') }} au {{ Carbon::parse($ev->dateFin)->format('d M Y') }}</div>
                    <div class="body-text">{{ substr($ev->heure, 0, 5) }}</div>
                    <div>
                        @if ($ev->status == 'inactif')
                        <div class="block-pending">{{ ucfirst($ev->status) }}</div>
                        @elseif ($ev->status == 'en cours')
                        <div class="block-pending">{{ ucfirst($ev->status) }}</div>
                        @elseif ($ev->status == 'annulé')
                        <div class="block-not-available">{{ ucfirst($ev->status) }}</div>
                        @elseif ($ev->status == 'actif')
                        <div class="block-tracking">{{ ucfirst($ev->status) }}</div>
                        @elseif ($ev->status == 'terminé')
                        <div class="block-published">{{ ucfirst($ev->status) }}</div>
                        @endif
                    </div>

                    @if($ev->datePublication )
                    <div class="body-text">
                        {{ Carbon::parse($ev->datePublication)->format('d M Y')}}
                    </div>
                    @else
                    <div class="body-text">Non publié</div>
                    @endif
                    <div class="list-icon-function">
                        <div class="">
                            <a href="{{ route('form-showEvent', ['id' => $ev->id]) }}">
                                <i class="icon-edit tf-button style-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="product-item gap14">
                <div class="flex items-center justify-center w-full">
                    <p>Aucun évènement trouvé.</p>
                </div>
            </li>
            @endforelse
        </ul>

    </div>
    <div class="divider"></div>
    <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Compteur : {{ $event->count() }} évènement(s)</div>
        <ul class="wg-pagination">
            {{ $event->links('vendor.pagination.custom') }}
        </ul>
    </div>
</div>

<script>
    function applyFilter() {
        var filterOption = document.getElementById('filterOption').value;
        var url = "{{ route('event') }}";

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

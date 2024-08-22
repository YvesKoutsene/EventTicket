@extends('admin.include.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMdfh7zbeJvWqFb7wPp4Oftr9+8eX8jNynjLfV3" crossorigin="anonymous">
<div class="flex items-center flex-wrap justify-between gap20 mb-27" id="snackbar">
    <h3>Billets</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href=""><div class="text-tiny">Billets</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Liste Billet</div>
        </li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <div class="show">
                <div class="text-tiny">Affichage</div>
                <div class="select">
                    <form action="{{ route('billet') }}" method="GET" id="entriesForm">
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
                        <option value="type" {{ request('filter') == 'type' ? 'selected' : '' }}>Par type
                        <option value="event" {{ request('filter') == 'event' ? 'selected' : '' }}>Par évènement
                    </select>
                </div>
                <button class="tf-button style-1 w128" onclick="applyFilter()">Filtrer</button>
            </div>
            <form class="form-search">

            </form>
        </div>
        <a class="tf-button style-1 w208" href=""><i class="icon-refresh-ccw"></i>Actualiser</a>
    </div>

    <div class="wg-table table-all-category">
        <ul class="table-title flex gap20 mb-14">
            <li>
                <div class="body-title">Billet(Place)</div>
            </li>
            <li>
                <div class="body-title">Evènement</div>
            </li>
            <li>
                <div class="body-title">Prix (FCFA)</div>
            </li>
            <li>
                <div class="body-title">Quota</div>
            </li>
            <li>
                <div class="body-title">Reste</div>
            </li>
            <li>
                <div class="body-title">Statut</div>
            </li>
            <li>
                <div class="body-title">Date création</div>
            </li>
        </ul>
        <ul>
            @forelse($billet as $bil)
            <li class="product-item gap14">
                <div class="image no-bg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket-detailed-fill" viewBox="0 0 16 16">
                        <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4 1a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5m0 5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5M4 8a1 1 0 0 0 1 1h6a1 1 0 1 0 0-2H5a1 1 0 0 0-1 1"/>
                    </svg>
                </div>
                <div class="flex items-center justify-between gap20 flex-grow">
                    <div class="name">
                        <a href="{{ route ('form-showBillet', [ 'id' => $bil->id]) }}" class="body-title-2">{{ $bil->typeBillet->nom }}({{ $bil->nombre }})</a>
                    </div>
                    <div class="body-text">{{ $bil->evenement->nom }} ({{ $bil->evenement->type }})</div>
                    <div class="body-text">{{ $bil->prix }}</div>
                    <div class="body-text">{{ $bil->quota }}</div>
                    <div class="body-text">{{ $bil->rest }}</div>
                    <div>
                        @if ($bil->status == 'inactif')
                        <div class="block-pending">{{ ucfirst($bil->status) }}</div>
                        @elseif ($bil->status == 'annulé')
                        <div class="block-not-available">{{ ucfirst($bil->status) }}</div>
                        @elseif ($bil->status == 'ouvert')
                        <div class="block-tracking">{{ ucfirst($bil->status) }}</div>
                        @elseif ($bil->status == 'vendu')
                        <div class="block-published">{{ ucfirst($bil->status) }}</div>
                        @elseif ($bil->status == 'fermé')
                        <div class="block-pending">{{ ucfirst($bil->status) }}</div>
                        @endif
                    </div>
                    <div class="body-text">{{ $bil->created_at->format('d M Y') }}</div>
                </div>
            </li>
            @empty
            <li class="product-item gap14">
                <div class="flex items-center justify-center w-full">
                    <p>Aucun billet trouvé.</p>
                </div>
            </li>
            @endforelse
        </ul>
    </div>
    <div class="divider"></div>
    <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Compteur : {{ $billet->count() }} billet(s)</div>
        <ul class="wg-pagination">
            {{ $billet->links('vendor.pagination.custom') }}
        </ul>
    </div>
</div>

<script>
    function applyFilter() {
        var filterOption = document.getElementById('filterOption').value;
        var url = "{{ route('billet') }}";

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

    function confirmDeleteBillet(billetId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette opération est irréversible.')) {
            document.getElementById('deleteForm' + billetId).submit();
        }
    }

</script>

@endsection

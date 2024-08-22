@extends('admin.include.layouts.app')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Types Billet</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href=""><div class="text-tiny">Types</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Liste Type</div>
        </li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <div class="show">
                <div class="text-tiny">Affichage</div>
                <div class="select">
                    <form action="{{ route('type') }}" method="GET" id="entriesForm">
                        <select name="perPage" id="perPage" onchange="document.getElementById('entriesForm').submit();">
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10
                            <option value="30" {{ request('perPage') == 30 ? 'selected' : '' }}>30
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100
                        </select>
                    </form>
                </div>
                <div class="text-tiny">entrées</div>
            </div>
            <form class="form-search">
                <fieldset class="name">
                    <input type="text" placeholder="Rechercher type ici..." class="" name="name" tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('form-type') }}"><i class="icon-plus"></i>Ajouter categorie</a>
    </div>

    <div class="wg-table table-all-category">
        <ul class="table-title flex gap20 mb-14">
            <li>
                <div class="body-title">Type</div>
            </li>
            <li>
                <div class="body-title">Identifiant</div>
            </li>
            <li>
                <div class="body-title">Description</div>
            </li>
            <li>
                <div class="body-title">Date création</div>
            </li>
            <li>
                <div class="body-title">Actions</div>
            </li>
        </ul>
        <ul>
            @foreach($type as $t)
            <li class="product-item gap14">
                <div class="image no-bg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M20 2H4C3.44772 2 3 2.44772 3 3V21C3 21.5523 3.44772 22 4 22H20C20.5523 22 21 21.5523 21 21V3C21 2.44772 20.5523 2 20 2Z" fill="#111111"/>
                        <path d="M7 7H17V9H7V7ZM7 11H17V13H7V11ZM7 15H13V17H7V15Z" fill="white"/>
                    </svg>
                </div>
                <div class="flex items-center justify-between gap20 flex-grow">
                    <div class="name">
                        <a href="{{ route('form-updateType', ['id' => $t->id]) }}" class="body-title-2">{{ $t->nom }}</a>
                    </div>
                    <div class="body-text">#{{ $t->id }}</div>
                    <div class="body-text">{{ $t->description }}</div>
                    <div class="body-text">{{ $t->created_at->format('d M Y') }}</div>
                    <div class="list-icon-function">
                        @if ($t->billets->isEmpty() )
                        <div class="item edit">
                            <a href="{{ route('form-updateType', ['id' => $t->id]) }}">
                                <i class="icon-edit-3 tf-button style-1"></i>
                            </a>
                        </div>
                        <div class="">
                            <form id="deleteForm{{ $t->id }}" action="{{ route('deleteType', ['id' => $t->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="#" onclick="confirmDeleteType('{{ $t->id }}')" type="button">
                                    <i class="icon-trash-2 tf-button style-1"></i>
                                </a>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="divider"></div>
    <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Compteur : {{ $type->count() }} type(s) billet</div>
        <ul class="wg-pagination">
            {{ $type->links('vendor.pagination.custom') }}
        </ul>
    </div>
</div>

<script>
    function confirmDeleteType(typeId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce type ? Cette opération est irréversible.')) {
            document.getElementById('deleteForm' + typeId).submit();
        }
    }
</script>
@endsection

@extends('admin.include.layouts.app')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27" id="snackbar">
    <h3>Categories Evènement</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href=""><div class="text-tiny">Categories</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Liste Categorie</div>
        </li>
    </ul>
</div>
<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <div class="show">
                <div class="text-tiny">Affichage</div>
                <div class="select">
                    <form action="{{ route('categorie') }}" method="GET" id="entriesForm">
                        <select name="perPage" id="perPage" onchange="document.getElementById('entriesForm').submit();">
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}/>10
                            <option value="30" {{ request('perPage') == 30 ? 'selected' : '' }}/>30
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}/>50
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}/>100
                        </select>
                    </form>
                </div>
                <div class="text-tiny">entrées</div>
            </div>
            <form class="form-search">
                <fieldset class="name">
                    <input type="text" placeholder="Rechercher categorie ici..." class="" name="name" tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('form-categorie') }}"><i class="icon-plus"></i>Ajouter categorie</a>
    </div>

    <div class="wg-table table-all-category">
        <ul class="table-title flex gap20 mb-14">
            <li>
                <div class="body-title">Categorie</div>
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
            @foreach($categorie as $cat)
            <li class="product-item gap14">
                <div class="image no-bg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M3 3H21V21H3V3Z" fill="#111111"/>
                        <path d="M7 7H11V11H7V7ZM13 7H17V11H13V7ZM7 13H11V17H7V13ZM13 13H17V17H13V13Z" fill="white"/>
                    </svg>
                </div>
                <div class="flex items-center justify-between gap20 flex-grow">
                    <div class="name">
                        <a href="{{ route('form-updateCatEvent', ['id' => $cat->id]) }}" class="body-title-2">{{ $cat->nom }}</a>
                    </div>
                    <div class="body-text">#{{ $cat->id }}</div>
                    <div class="body-text">{{ $cat->description }}</div>
                    <div class="body-text">{{ $cat->created_at->format('d M Y') }}</div>
                    <div class="list-icon-function">
                            @if ($cat->evenements->isEmpty() )
                            <div class="item edit">
                                <a href="{{ route('form-updateCatEvent', ['id' => $cat->id]) }}">
                                    <i class="icon-edit-3 tf-button style-1"></i>
                                </a>
                            </div>
                            <div class="">
                                <form id="deleteForm{{ $cat->id }}" action="{{ route('deleteCatEvent', ['id' => $cat->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="#" onclick="confirmDeleteCatEvent('{{ $cat->id }}')" type="button">
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
        <div class="text-tiny">Compteur : {{ $categorie->count() }} categorie(s)</div>
        <ul class="wg-pagination">
            {{ $categorie->links('vendor.pagination.custom') }}
        </ul>
    </div>
</div>

<script>
    function confirmDeleteCatEvent(categorieId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette categorie ? Cette opération est irréversible.')) {
            document.getElementById('deleteForm' + categorieId).submit();
        }
    }
</script>

@endsection


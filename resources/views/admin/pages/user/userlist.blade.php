@extends('admin.include.layouts.app')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27" id="snackbar">
    <h3>Utilisateurs</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href=""><div class="text-tiny">Utilisateur</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Liste Utilisateur</div>
        </li>
    </ul>
</div>
<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <div class="show">
                <div class="text-tiny">Affichage</div>
                <div class="select">
                    <form action="" method="GET" id="entriesForm">
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
                    <input type="text" placeholder="Rechercher utilisateur ici..." class="" name="name" tabindex="2" value="" aria-required="true" required="">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
        <a class="tf-button style-1 w208" href="#"><i class="icon-refresh"></i>Actualliser</a>
    </div>

    <div class="wg-table table-all-category">
        <ul class="table-title flex gap20 mb-14">
            <li>
                <div class="body-title">Utilisateur</div>
            </li>
            <li>
                <div class="body-title">Email</div>
            </li>
            <li>
                <div class="body-title">Numero</div>
            </li>
            <li>
                <div class="body-title">Role</div>
            </li>
            <li>
                <div class="body-title">Statut</div>
            </li>
            <li>
                <div class="body-title">Date création</div>
            </li>
            <li>
                <div class="body-title">Actions</div>
            </li>
        </ul>

        <ul>
            @forelse($user as $u)
            <li class="product-item gap14">
                <div class="image no-bg">
                    @if ($u->profile && $u->profile !== 'default_profile_url')
                    <img src="{{ asset($u->profile) }}" class="w-10 h-12 rounded-full object-contain" alt="Profile Image">
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C9.243 2 7 4.243 7 7C7 9.757 9.243 12 12 12C14.757 12 17 9.757 17 7C17 4.243 14.757 2 12 2zM12 10C10.346 10 9 8.654 9 7C9 5.346 10.346 4 12 4C13.654 4 15 5.346 15 7C15 8.654 13.654 10 12 10zM12 14C9.243 14 2 15.743 2 18.5V21C2 21.553 2.447 22 3 22H21C21.553 22 22 21.553 22 21V18.5C22 15.743 14.757 14 12 14zM4 20V18.5C4 17.532 7.654 16 12 16C16.346 16 20 17.532 20 18.5V20H4z" fill="#111111"/>
                    </svg>
                    @endif
                </div>
                <div class="flex items-center justify-between gap20 flex-grow">
                    <div class="name">
                        <!--
                        <a href="{{ route('form-updateuser', ['id' => $u->id]) }}" class="body-title-2">{{ $u->name }}</a>
                        -->
                        <a href="" class="body-title-2">{{ $u->name }}</a>
                    </div>
                    <div class="body-text">{{ $u->email }}</div>
                    <div class="body-text">{{ $u->number }}</div>
                    <div class="body-text">{{ $u->role }}</div>
                    <div class="body-text">
                        @if ($u->status == 'actif')
                        <div class="block-published">{{ ucfirst($u->status) }}</div>
                        @else
                        <div class="block-pending">{{ ucfirst($u->status) }}</div>
                        @endif
                    </div>
                    <div class="body-text">{{ $u->created_at->format('d M Y') }}</div>
                    <div class="list-icon-function">
                        <!--

                        @if ($u->role == 'organizer')
                        <div class="item edit ">
                            <a href="{{ route('form-updateuser', ['id' => $u->id]) }}">
                                <i class="icon-edit-3 tf-button style-1"></i>
                            </a>
                        </div>
                        @endif
                        -->
                        <div>
                            @if ($u->status == 'actif')
                            <a class="tf-button style-1 w0 h-0" href="{{ route('userDesactivate', ['id' => $u->id]) }}" title="Désactiver">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                </svg>
                            </a>
                            @else
                            <a class="tf-button style-1 w0 h-0" href="{{ route('userActivate', ['id' => $u->id]) }}" title="Activer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <li class="product-item gap14">
                    <div class="flex items-center justify-center w-full">
                        <p>Aucun utilisateur trouvé.</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>
    <div class="divider"></div>
    <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Compteur : {{ $user->count() }} utilisateur(s)</div>
        <ul class="wg-pagination">
            {{ $user->links('vendor.pagination.custom') }}
        </ul>
    </div>
</div>
@endsection

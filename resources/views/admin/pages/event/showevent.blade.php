@extends('admin.include.layouts.app')

@section('content')

@php
use Carbon\Carbon;
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Champ caché pour la désapprobation -->
<form id="disapproveForm" method="POST" action="" style="display: none;">
    @csrf
    <input type="hidden" name="motif" id="motifInputHidden">
    <input type="hidden" name="_method" value="PATCH">
</form>

<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Information Evènement</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href="{{ route('event') }}"><div class="text-tiny">Evenements</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Details Evènement</div>
        </li>
    </ul>
</div>

<form method="" enctype="multipart/form-data">
    <div class="tf-section-2 form-add-product">
        <div class="wg-box">
            <fieldset>
                <div class="body-title mb-10">Affiche de l'évènement</div>
                <div class="upload-image mb-16">
                    <div class="item">
                        <a value="" id="image-preview-link">
                            <img id="image-preview-img" src="{{ $event->image ? asset($event->image) : '' }}" alt="Aperçu de l'image" style="max-width: 100%; max-height: 200px;">
                        </a>
                    </div>
                </div>
            </fieldset>
            <div class="cols gap22">
                <fieldset class="name">
                    <div class="body-title mb-10">Type d'évènement</div>
                    <input class="mb-10" type="text" placeholder="Type de l'évènement" name="type" tabindex="0" value="{{ old('type', $event->type) }}" aria-required="true" readonly>
                </fieldset>
                <fieldset class="name">
                    <div class="body-title mb-10">Nombre de place</div>
                    <input class="mb-10" type="text" placeholder="Nombre de place" name="place" tabindex="0" value="{{ old('nom', $event->place) }}" aria-required="true" readonly>
                </fieldset>
                <fieldset class="male">
                    <div class="body-title mb-10">Categorie</div>
                    <div class="">
                        <select class="" name="categorie_evenement_id" disabled>
                            <option value="{{ $event->categorie->id }}">#{{ $event->categorie->id }} {{ $event->categorie->nom }}</option>
                        </select>
                    </div>
                </fieldset>
            </div>
            <fieldset class="name">
                <div class="body-title mb-10">Nom</div>
                <input class="mb-10" type="text" placeholder="Nom de l'évènement" name="nom" tabindex="0" value="{{ old('nom', $event->nom) }}" aria-required="true" readonly>
            </fieldset>
            <fieldset class="name">
                <div class="body-title mb-10">Lieu</div>
                <input class="mb-10" type="text" placeholder="Lieu de l'évènement" name="lieu" tabindex="0" value="{{ old('lieu', $event->lieu) }}" aria-required="true" readonly>
            </fieldset>
            <fieldset class="description">
                <div class="body-title mb-10">Description</div>
                <textarea class="mb-10" name="description" placeholder="Description de l'évènement" tabindex="0" aria-required="true" readonly>{{ old('description', $event->description) }}</textarea>
            </fieldset>

            @if ($event->status == 'en cours')
            <div class="cols gap10">
                <button class="tf-button style-1 w-full" type="button" onclick="confirmApprovedEvent('{{ route('approvedEvent', ['id' => $event->id]) }}')">Approuver</button>
                <button class="tf-button style-1 w-full" type="button" onclick="setDisapproveRoute('{{ route('desapprovedEvent', ['id' => $event->id]) }}')">Désapprouver</button>
            </div>
            @endif
        </div>

        <div class="wg-box">
            <div class="cols gap22">
                <fieldset class="name">
                    <div class="body-title mb-10">Date début</div>
                    <div class="">
                        <input type="text" id="" name="dateDebut" value="{{ old('dateDebut', Carbon::parse($event->dateDebut)->format('d M Y')) }}" readonly>
                    </div>
                </fieldset>
                <fieldset class="">
                    <div class="body-title mb-10">Heure</div>
                    <div class="">
                        <input type="text" id="" name="heure" value="{{ old('heure', substr($event->heure, 0, 5)) }}" readonly>
                    </div>
                </fieldset>
                <fieldset class="name">
                    <div class="body-title mb-10">Date fin</div>
                    <div class="">
                        <input type="text" id="" name="" value="{{ old('dateFin', Carbon::parse($event->dateFin)->format('d M Y')) }}" readonly>
                    </div>
                </fieldset>
            </div>
            <fieldset class="category">
                <div class="body-title mb-10">Organisateur</div>
                <div class="">
                    <select class="" name="organizer_id" disabled>
                        <option value="{{ $event->user->id }}">{{ $event->user->name }} | {{ $event->user->email }}</option>
                    </select>
                </div>
            </fieldset>
            <div class="wg-box">
                <h3>Détails des Billets</h3>
                <div class="wg-table table-all-category">
                    <ul class="table-title flex gap20 mb-14">
                        <li>
                            <div class="body-title">Billet(Personne)</div>
                        </li>
                        <li>
                            <div class="body-title">Prix</div>
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
                    </ul>

                    <ul>
                        @forelse ($event->billets as $billet)
                        <li class="product-item gap14">
                            <div class="image no-bg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket-detailed-fill" viewBox="0 0 16 16">
                                    <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4 1a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5m0 5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5M4 8a1 1 0 0 0 1 1h6a1 1 0 1 0 0-2H5a1 1 0 0 0-1 1"/>
                                </svg>
                            </div>
                            <div class="flex items-center justify-between gap20 flex-grow">
                                <div class="name">
                                    <a href="{{ route ('form-showBillet', [ 'id' => $billet->id]) }}" class="body-title-2">{{ $billet->typeBillet->nom }}({{ $billet->nombre }})</a>
                                </div>
                                <div class="body-text">{{ $billet->prix }}</div>
                                <div class="body-text">{{ $billet->quota }}</div>
                                <div class="body-text">{{ $billet->rest }}</div>
                                <div>
                                    @if ($billet->status == 'inactif')
                                    <div class="block-pending">{{ ucfirst($billet->status) }}</div>
                                    @elseif ($billet->status == 'ouvert')
                                    <div class="block-tracking">{{ ucfirst($billet->status) }}</div>
                                    @elseif ($billet->status == 'annulé')
                                    <div class="block-not-available">{{ ucfirst($billet->status) }}</div>
                                    @elseif ($billet->status == 'vendu')
                                    <div class="block-published">{{ ucfirst($billet->status) }}</div>
                                    @elseif ($billet->status == 'fermé')
                                    <div class="block-pending">{{ ucfirst($billet->status) }}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="product-item gap14">
                            <div class="flex items-center justify-center w-full">
                                <p>Aucun billet créé.</p>
                            </div>
                        </li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>
</form>
<script>
    function confirmApprovedEvent(url) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous allez approuver cet événement!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<span style="font-size: 18px; font-weight: bold;">Oui, approuver!</span>',
            cancelButtonText: '<span style="font-size: 18px; font-weight: bold;">Annuler</span>',
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn'
            },
            backdrop: true,
            allowOutsideClick: false,
            animation: true,
            position: 'center'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function setDisapproveRoute(url) {
        Swal.fire({
            title: 'Motif',
            input: 'textarea',
            inputPlaceholder: 'Raisons pour la désapprobation...',
            showCancelButton: true,
            confirmButtonText: '<span style="font-size: 18px; font-weight: bold;">Désapprouver</span>',
            cancelButtonText: '<span style="font-size: 18px; font-weight: bold;">Annuler</span>',
            customClass: {
                title: 'swal-title',
                content: 'swal-content',
                input: 'swal-input',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn'
            },
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage(
                        '<span style="font-size: 16px; color: red;">Le motif est requis!</span>'
                    );
                } else {
                    return value;
                }
            },
            backdrop: true,
            allowOutsideClick: false,
            animation: true,
            position: 'center'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('motifInputHidden').value = result.value;
                document.getElementById('disapproveForm').action = url;
                document.getElementById('disapproveForm').submit();
            }
        });
    }
</script>

<style>
    .swal-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }
    .swal-content {
        font-size: 16px;
        color: #555;
    }
    .swal-input {
        border: 2px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
    }
    .swal-confirm-btn {
        background-color: #3085d6;
        color: white;
        font-weight: bold;
        border-radius: 5px;
    }
    .swal-cancel-btn {
        background-color: #d33;
        color: white;
        font-weight: bold;
        border-radius: 5px;
    }
</style>


@endsection

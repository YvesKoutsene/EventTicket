@extends('organizer.include.layouts.app')

@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Evènement</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="">Mes Evènements</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a >Liste</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Mes évènements</h4>
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
                            <i class="fa fa-arrow-circle-up"></i>
                             Publier Evènement
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header no-bd">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold">
                                            Nouvelle publication
                                        </span>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="small">Rendre public votre évènement, assurez-vous d'avoir vérifié tous les billets concernés</p>
                                    <form id="publishEventForm" data-action="{{ route('publishMyEvent', ['eventId' => ':eventId']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group form-group-default">
                                                    <label>Evènement</label>
                                                    <select class="form-control input-border-bottom" id="event-select" name="eventId" required>
                                                        <option value="">Sélectionner un évènement</option>
                                                        @forelse($events as $event)
                                                            @if(($event->status == 'inactif' && $event->placeRestant == 0)  && $event->billets->isNotEmpty() )
                                                            <option value="{{ $event->id }}">{{ $event->nom }} ({{ $event->type }} de {{$event->place}})</option>
                                                            @else
                                                            <option disabled>{{ $event->nom }} ({{ $event->type }})</option>
                                                            @endif
                                                        @empty
                                                        <option disabled>Aucun évènement trouvé</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group form-group-default">
                                                    <label>Billets</label>
                                                    <div id="billet-info">
                                                        <p>Sélectionnez un événement pour voir ses billets.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input id="terms-checkbox" class="form-check-input" type="checkbox" value="">
                                                    <span class="form-check-sign">Publier maintenant<span class="tf-color-1">*</span></span>
                                                </label>
                                            </div>
                                            -->
                                        </div>
                                        <div class="modal-footer no-bd">
                                            <button type="submit" class="btn btn-primary" id="publish-button" name="publish">Publier</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Affiche</th>
                                <th>Evènement</th>
                                <th>Categorie</th>
                                <th>Lieu</th>
                                <th>Description</th>
                                <th>Déroulement</th>
                                <th>Statut</th>
                                <th>Publié le</th>
                                <th style="width: 10%">Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Affiche</th>
                                <th>Evènement</th>
                                <th>Categorie</th>
                                <th>Lieu</th>
                                <th>Description</th>
                                <th>Déroulement</th>
                                <th>Statut</th>
                                <th>Publié le</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td><img src="{{ asset($event->image) }}" alt="{{ $event->nom }}" class="event-logo"></td>
                                <td>{{ $event->nom }} ({{ $event->type }} de {{ $event->place }} places)</td>
                                <td>{{ $event->categorie->nom }}</td>
                                <td>{{ $event->lieu }}</td>
                                <td>
                                    <div id="description-short-{{ $event->id }}">
                                        {{ \Illuminate\Support\Str::limit($event->description, 10) }}
                                        @if(strlen($event->description) > 10)
                                        <a href="javascript:void(0);" data-id="{{ $event->id }}" class="toggle-description">Plus</a>
                                        @endif
                                    </div>

                                    <div id="description-full-{{ $event->id }}" style="display:none;">
                                        {{ $event->description }}
                                        <a href="javascript:void(0);" data-id="{{ $event->id }}" class="toggle-description">Moins</a>
                                    </div>
                                </td>
                                <td>{{ Carbon::parse($event->dateDebut)->format('d M Y') }} au {{ Carbon::parse($event->dateFin)->format('d M Y') }} à partir de {{ substr($event->heure, 0, 5) }}</td>
                                <td class="{{ 'status-' . strtolower($event->status) }}">{{ ucfirst($event->status)}}</td>
                                @if($event->datePublication )
                                    <td>{{ Carbon::parse($event->datePublication)->format('d M Y')}}</td>
                                @else
                                    <td>Non publié</td>
                                @endif
                                <td>
                                    @if ($event->status == 'inactif')
                                        <div class="form-button-action">
                                            <a href="{{ route('form-updateMyEvent', $event->id) }}" data-toggle="tooltip" title="Editer" class="btn btn-link btn-primary btn-lg" data-original-title="Editer">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form id="delete-event-form-{{ $event->id }}" action="{{ route('deleteMyEvent', $event->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" data-toggle="tooltip" title="Supprimer" class="btn btn-link btn-danger alert-delete-event" data-original-title="Supprimer" data-event-id="{{ $event->id }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    @elseif ($event->status == 'actif')
                                    <div class="form-button-action">
                                        <form id="canceled-event-form-{{ $event->id }}" action="{{ route('canceledMyEvent', $event->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        <button type="button" data-toggle="tooltip" title="Annuler" class="btn btn-link btn-danger alert-canceled-event" data-original-title="Annuler" data-canceled-event-id="{{ $event->id }}">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                    </div>
                                    @elseif ($event->status == 'rejeté')
                                        <div class="form-button-action">
                                            <button type="button" data-toggle="modal" data-target="#motifModal{{ $event->id }}" class="btn btn-link btn-primary btn-lg" title="Voir motif">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <div class="modal fade" id="motifModal{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="motifModalLabel{{ $event->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title" id="motifModalLabel{{ $event->id }}">Motif du Rejet</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-wrap">{{ $event->motif }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-button-action">
                                            <button type="button" data-toggle="tooltip" title="Pas d'action" class="btn btn-link btn-primary btn-lg" data-original-title="Pas d'action"></button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status-inactif {
        color: gray;
    }

    .status-en.cours {
        color: dodgerblue;
    }

    .status-annulé {
        color: red;
    }

    .status-actif {
        color: blue;
    }

    .status-terminé {
        color: green;
    }

    .status-fermé {
        color: greenyellow;
    }

    .event-logo {
        width: 40px;
        height: auto;
        object-fit: cover;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-footer {
        border-top: none;
        padding-top: 1rem;
    }

</style>

<script>
    document.getElementById('event-select').addEventListener('change', function() {
        const eventId = this.value;

        if (eventId) {
            fetch(`/organisateur/mes-billets-évènement/${eventId}/`)
                .then(response => response.json())
                .then(data => {
                    const billetInfo = document.getElementById('billet-info');
                    billetInfo.innerHTML = '';

                    if (data.billets && data.billets.length > 0) {
                        const billetTable = document.createElement('table');
                        billetTable.classList.add('table', 'table-bordered');
                        billetTable.innerHTML = `
                            <thead>
                                <tr>
                                    <th>Billet(Personne)</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Quota</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        `;

                        const tbody = billetTable.querySelector('tbody');

                        data.billets.forEach(billet => {
                            const billetRow = document.createElement('tr');
                            billetRow.innerHTML = `
                                <td>${billet.type_billet.nom}(${billet.nombre})</td>
                                <td>${billet.prix}</td>
                                <td>${billet.quota}</td>
                            `;
                            tbody.appendChild(billetRow);
                        });

                        billetInfo.appendChild(billetTable);
                        const totalBillets = document.createElement('p');
                        totalBillets.textContent = `Nombre total de billets créés : ${data.billets.length}`;
                        billetInfo.appendChild(totalBillets);
                    } else {
                        billetInfo.textContent = 'Aucun billet créé pour le moment.';
                    }
                })
                .catch(error => {
                    console.error('Error fetching billets:', error);
                    document.getElementById('billet-info').innerHTML = '<p>Erreur lors de la récupération des billets.</p>';
                });
        } else {
            document.getElementById('billet-info').innerHTML = '<p>Sélectionnez un événement pour voir ses billets.</p>';
        }
    });

    document.getElementById('publishEventForm').addEventListener('submit', function(event) {
        /*const checkbox = document.getElementById('terms-checkbox');
        if (!checkbox.checked) {
            event.preventDefault();
            alert('Veuillez accepter les termes et conditions pour publier l\'événement.');
            return;
        }
*/
        var eventSelect = document.getElementById('event-select');
        var selectedEventId = eventSelect.value;
        if (selectedEventId) {
            var form = document.getElementById('publishEventForm');
            var action = form.getAttribute('data-action').replace(':eventId', selectedEventId);
            form.setAttribute('action', action);
        } else {
            event.preventDefault();
            alert('Veuillez sélectionner un événement.');
        }
    });

    //
    document.addEventListener("DOMContentLoaded", function() {
        function getQueryParam(param) {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        if (getQueryParam('showModal') === 'true') {
            $('#addRowModal').modal('show');
        }
    });

    //
    document.addEventListener("DOMContentLoaded", function () {
        var toggleLinks = document.querySelectorAll(".toggle-description");

        toggleLinks.forEach(function (link) {
            link.addEventListener("click", function () {
                var id = this.getAttribute("data-id");
                var shortDesc = document.getElementById("description-short-" + id);
                var fullDesc = document.getElementById("description-full-" + id);

                if (shortDesc.style.display === "none") {
                    shortDesc.style.display = "block";
                    fullDesc.style.display = "none";
                } else {
                    shortDesc.style.display = "none";
                    fullDesc.style.display = "block";
                }
            });
        });
    });

</script>

@endsection

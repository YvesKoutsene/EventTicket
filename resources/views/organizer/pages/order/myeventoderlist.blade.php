@extends('organizer.include.layouts.app')
@section('content')

@php
use Carbon\Carbon;
// Initialiser la variable totalRevenueByEvent
$totalRevenueByEvent = [];
@endphp

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Commande</h4>
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
                <a href="">Mes Ventes</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a>Liste</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Liste des Commandes</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Commandé Par</th>
                                <th>Événement</th>
                                <th>Billet(Place)</th>
                                <th>Nombre de Tickets</th>
                                <th>Prix Total(FCFA)</th>
                                <th>Date de Commande</th>
                                <th style="width: 10%">Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Commandé Par</th>
                                <th>Événement</th>
                                <th>Billet</th>
                                <th>Nombre de Tickets</th>
                                <th>Prix Total</th>
                                <th>Date de Commande</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($commandes as $commande)
                            @php
                            $eventName = $commande->billet->evenement->nom;
                            if (!isset($totalRevenueByEvent[$eventName])) {
                            $totalRevenueByEvent[$eventName] = 0;
                            }
                            $totalRevenueByEvent[$eventName] += $commande->prixTotal;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $commande->user->profile }}" alt="Profile Image" class="img-thumbnail" style="width: 50px; height: 50px; border-radius: 50%;">
                                        <span class="ml-2">{{ $commande->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $commande->billet->evenement->nom }} ({{ $commande->billet->evenement->type }})</td>
                                <td>{{ $commande->billet->typeBillet->nom }}({{ $commande->billet->nombre }})</td>
                                <td>{{ $commande->nombreTicket }}</td>
                                <td>{{ $commande->prixTotal }}</td>
                                <td>{{ Carbon::parse($commande->created_at)->format('d M Y') }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#ticket-details-{{ $commande->id }}" title="Voir tickets">
                                        <i class="fa fa-ticket-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Recette par Événement</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($totalRevenueByEvent as $eventName => $totalRevenue)
                        <li class="list-group-item">{{ $eventName }}: {{ $totalRevenue }} FCFA</li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modales pour les détails des tickets -->
@foreach($commandes as $commande)
<div class="modal fade" id="ticket-details-{{ $commande->id }}" tabindex="-1" role="dialog" aria-labelledby="ticketDetailsLabel-{{ $commande->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketDetailsLabel-{{ $commande->id }}">Détails des Tickets pour la Commande {{ $commande->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Code QR</th>
                        <th>Numéro</th>
                        <th>Status</th>
                        <th>Date d'Expiration</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($commande->tickets as $ticket)
                    <tr>
                        <td>
                            <div class="qr-code-container">
                                <img src="{{ asset('storage/' . $ticket->codeQr) }}" alt="QR Code" class="qr-code-img">
                                <div class="qr-code-zoom">
                                    <img src="{{ asset('storage/' . $ticket->codeQr) }}" alt="QR Code agrandi" style="width: 200px; height: 200px;">
                                </div>
                            </div>
                        </td>
                        <td>{{ $ticket->numero }}</td>
                        <td class="{{ $ticket->status }}">
                            <span class="badge badge-{{ $ticket->status }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ Carbon::parse($ticket->dateExpiration)->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    .img-thumbnail {
        border: 1px solid #ddd;
        padding: 4px;
        border-radius: 50%;
    }
    .badge-actif {
        background-color: blue;
        color: white;
    }
    .badge-expiré {
        background-color: red;
        color: white;
    }
    .badge-annulé {
        background-color: darkred;
        color: white;
    }
    .badge-remboursé {
        background-color: darkblue;
        color: white;
    }
    .badge-utilisé {
        background-color: green;
        color: white;
    }
    .qr-code-container {
        position: relative;
        display: inline-block;
    }
    .qr-code-img {
        width: 50px;
        height: 50px;
        cursor: pointer;
    }
    .qr-code-zoom {
        display: none;
        position: absolute;
        top: 0;
        left: 60px;
        z-index: 100;
        border: 1px solid #ccc;
        background-color: white;
        padding: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
    }
    .qr-code-container:hover .qr-code-zoom {
        display: block;
    }
</style>

@endsection

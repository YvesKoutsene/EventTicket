@extends('organizer.include.layouts.app')
@section('content')

@php
use Carbon\Carbon;
@endphp
<script src="\assetsor/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Tableau de bord</h2>
                <h5 class="text-white op-7 mb-2">EvenTicket Tableau de Bord Organisateur</h5>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="{{ route ('mySalle') }}" class="btn btn-secondary btn-round">Commande</a>
            </div>
        </div>
    </div>
</div>
<div class="page-inner mt--5">
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="flaticon-calendar"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Évènement</p>
                                <h4 class="card-title">{{ $totalEvenements }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="flaticon-price-tag"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Billet</p>
                                <h4 class="card-title">{{ $totalBillets }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="flaticon-chat-8"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Avis</p>
                                <h4 class="card-title">{{ $totalAvis }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="flaticon-file"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Totale Commande</p>
                                <h4 class="card-title">{{ $totalCommandes }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="flaticon-coins"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Revenu</p>
                                <h4 class="card-title">{{ $totalRevenus }} FCFA</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Fluctuation des Avis, Commandes et Revenus</div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px; position: relative;">
                        <div id="noDataMessage" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        </div>
                        <canvas id="statisticsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Graphique en Anneau des Evènements</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="doughnutChart" style="width: 50%; height: 50%"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Commande Recente</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover" >
                            <thead>
                            <tr>
                                <th>Commande de</th>
                                <th>Événement</th>
                                <th>Billet(Place)</th>
                                <th>Nombre de Tickets</th>
                                <th>Prix Total (FCFA)</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Commande de</th>
                                <th>Événement</th>
                                <th>Billet(Place)</th>
                                <th>Nombre de Tickets</th>
                                <th>Prix Total (FCFA)</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($commandes as $commande)
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
@endsection

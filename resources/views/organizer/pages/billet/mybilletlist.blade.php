@extends('organizer.include.layouts.app')

@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Billet</h4>
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
                <a href="">Mes Billets</a>
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
                        <h4 class="card-title">Mes billets</h4>
                        <button type="button" data-target="#addRowModal"  data-toggle="modal" class="btn btn-primary btn-round ml-auto" data-original-title="Editer" onclick="window.location.href='{{ route('form-myBillet')}}'">
                            <i class="fa fa-plus"></i>
                            Ajouter Billet
                        </button>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Billet(Personne)</th>
                                <th>Evènement</th>
                                <th>Prix(FCFA)</th>
                                <th>Quota</th>
                                <th>Reste</th>
                                <th>Statut</th>
                                <th style="width: 10%">Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Billet(place)</th>
                                <th>Evènement</th>
                                <th>Prix</th>
                                <th>Quota</th>
                                <th>Reste</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($billets as $billet)
                            <tr>
                                <td>{{ $billet->typeBillet->nom }}({{ $billet->nombre }})</td>
                                <td>{{ $billet->evenement->nom }} ({{ $billet->evenement->type }} de {{ $billet->evenement->place }} places)</td>
                                <td>{{ $billet->prix }}</td>
                                <td>{{ $billet->quota }}</td>
                                <td>{{ $billet->rest }}</td>
                                <td class="{{ 'status-' . strtolower($billet->status) }}" >{{ ucfirst($billet->status) }}</td>
                                <td>
                                    @if(($billet->status == "inactif" && $billet->evenement->status == "inactif") && $billet->typeBillet->nom !== "Gratuit" )
                                        <div class="form-button-action">
                                                <a href="{{ route('form-updateMyBillet', $billet->id) }}" data-toggle="tooltip" title="Editer" class="btn btn-link btn-primary btn-lg" data-original-title="Editer">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form id="delete-billet-form-{{ $billet->id }}" action="{{ route('deleteMyBillet', $billet->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" data-toggle="tooltip" title="Supprimer" class="btn btn-link btn-danger alert-delete-billet" data-original-title="Supprimer" data-billet-id="{{ $billet->id }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
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

    .status-ouvert {
        color: blue;
    }

    .status-annulé{
        color: red;
    }

    .status-vendu {
        color: green;
    }

    .status-fermé {
        color: yellowgreen;
    }

</style>

@endsection

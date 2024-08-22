@extends('organizer.include.layouts.app')
@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Avis</h4>
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
                <a href="">Liste avis</a>
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
                        <h4 class="card-title">Liste des Avis</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Participant</th>
                                <th>Événement</th>
                                <th>Avis</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th style="width: 10%">Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Participant</th>
                                <th>Événement</th>
                                <th>Avis</th>
                                <th>Fait le</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($events as $event)
                            @foreach($event->avis as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $review->user->profile }}" alt="Profile Image" class="img-thumbnail" style="width: 50px; height: 50px; border-radius: 50%;">
                                        <span class="ml-2">{{ $review->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $event->nom }} ({{ $event->type }})</td>
                                <td>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                <span class="fa fa-star{{ $i <= $review->note ? '' : '-o' }}"></span>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mt-2">{{ $review->comment }}</p>
                                    </div>
                                </td>
                                <td>{{ Carbon::parse($review->created_at)->format('d M Y') }}</td>
                                <td class="{{ 'status-' . strtolower($review->status) }}">{{ ucfirst($review->status) }}</td>
                                <td>
                                    <div class="form-button-action">
                                        @if($review->status == 'actif')
                                        <form method="POST" action="{{ route('avis.bloquer', $review->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" data-toggle="tooltip" title="Bloquer" class="btn btn-link btn-danger">
                                                <i class="fa fa-times-circle fa-2x"></i>
                                            </button>
                                        </form>
                                        @else
                                        <form method="POST" action="{{ route('avis.debloquer', $review->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" data-toggle="tooltip" title="Débloquer" class="btn btn-link btn-primary">
                                                <i class="fa fa-check-circle fa-2x"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
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

    .status-actif {
        color: blue;
    }

    .img-thumbnail {
        border: 1px solid #ddd;
        padding: 4px;
        border-radius: 50%;
    }

    .rating .fa-star, .rating .fa-star-o {
        color: #FFD700;
        font-size: 15px;
    }

</style>

@endsection

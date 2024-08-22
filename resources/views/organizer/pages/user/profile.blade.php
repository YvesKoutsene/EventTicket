@extends('organizer.include.layouts.app')

@section('content')

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Compte</h4>
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
                <a href="">Mon Compte</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a >Profil</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-6 ml-auto mr-auto">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informations</div>
                </div>

                <div class="card card-profile">
                    <div class="profile-picture">
                        <div class="avatar avatar-xl">
                            <img src="{{ asset(Auth::user()->profile) }}"  alt="..." class="avatar-img rounded-circle">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="number">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="number" value="{{ Auth::user()->number }}" readonly>
                    </div>
                </div>
                <div class="card-footer">
                    <button  class="btn btn-primary" type="button" data-original-title="Editer" onclick="window.location.href='{{ route('myProfiledit') }}'">
                        <i class="fa fa-edit"></i>
                        Editer mon profil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('admin.include.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Information Billet</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href="{{ route('billet') }}"><div class="text-tiny">Billets</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Details Billet</div>
        </li>
    </ul>
</div>

<form class="form-add-new-user form-style-2" method="" action="" enctype="multipart/form-data">
    @csrf
    <div class="wg-box">
        <div class="left">
            <h5 class="mb-4">Billet<span class="tf-color-1">*</span></h5>
            <div class="body-text">Veuillez remplir les informations du billet</div>
        </div>
        <div class="right flex-grow">
            <fieldset class="name mb-24">
                <div class="body-title mb-10">Prix (FCFA)</div>
                <input id="prix" class="flex-grow" type="text" placeholder="Prix unitaire du billet" name="prix" tabindex="0" value="{{ old('prix', $billet->prix) }}" aria-required="true" readonly>
            </fieldset>
            <fieldset class="email mb-24">
                <div class="body-title mb-10">Quota</div>
                <input id="quota" class="flex-grow" type="text" placeholder="Nombre de tickets à vendre" name="quota" tabindex="0" value="{{ old('quota', $billet->quota) }}" aria-required="true" readonly>
            </fieldset>
            <fieldset class="email mb-24">
                <div class="body-title mb-10">Reste</div>
                <input id="quota" class="flex-grow" type="text" placeholder="Nombre de tickets restant" name="quota" tabindex="0" value="{{ old('rest', $billet->rest) }}" aria-required="true" readonly>
            </fieldset>
            <fieldset class="email mb-24">
                <div class="body-title mb-10">Statut</div>
                <input id="quota" class="flex-grow " type="text" placeholder="Statut du billet" name="quota" tabindex="0" value="{{ old('status', ucfirst($billet->status)) }}" aria-required="true" readonly>
            </fieldset>
        </div>
    </div>
    <div class="wg-box">
        <div class="left">
            <h5 class="mb-4">Type/Evènement<span class="tf-color-1">*</span></h5>
            <div class="body-text">Sélectionnez le type et l'évènement associés au billet</div>
        </div>
        <div class="right flex-grow">
            <fieldset class="mb-24">
                <div class="body-title mb-10">Type</div>
                <div class="">
                    <select class="flex-grow" name="typ_id" disabled>
                        <option value="{{ $billet->typeBillet->id }}">
                            #{{ $billet->typeBillet->id }} {{ $billet->typeBillet->nom }} : {{ $billet->typeBillet->description }}
                        </option>
                    </select>
                </div>
            </fieldset>
            <fieldset class="mb-24">
                <div class="body-title mb-10">Evènement</div>
                <div class="">
                    <select class="flex-grow" disabled name="eve_id">
                        <option value="{{ $billet->evenement->id }}">
                            #{{ $billet->evenement->id }} {{ $billet->evenement->nom }}
                        </option>
                    </select>
                </div>
            </fieldset>
        </div>
    </div>
</form>

@endsection

@extends('admin.include.layouts.app')

@section('content')

<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Informations Categorie</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href="{{ route('categorie') }}"><div class="text-tiny">Categories</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Modifier Categorie</div>
        </li>
    </ul>
</div>

<div class="wg-box">
    <form class="form-new-product form-style-1" method="POST" action="{{ route('updateCatEvent',  ['id' => $categorie->id]) }}">
        @csrf
        @method('PUT')
        <fieldset class="name">
            <div class="body-title">Nom <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" placeholder="Nom de la categorie d'évènement" name="nom" id="nom" tabindex="0" value="{{ old('nom', $categorie->nom) }}" aria-required="true" required>
        </fieldset>
        <fieldset class="description">
            <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
            <textarea class="mb-10" name="description" id="description" placeholder="Description de la categorie d'évènement" tabindex="0" aria-required="true" required>{{ old('description', $categorie->description) }}</textarea>
        </fieldset>
        <fieldset>
            <div></div>
            <div class="text-tiny">Ne dépassez pas 100 caractères lors de la saisie da la description de la categorie d'évènement.</div>
        </fieldset>
        <div class="bot">
            <div></div>
            <button class="tf-button w208" type="submit">Modifier</button>
        </div>
    </form>
</div>

@endsection

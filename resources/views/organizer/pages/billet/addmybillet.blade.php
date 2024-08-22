@extends('organizer.include.layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
                <a href="{{ route('myBillet') }}">Mes Billets</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a>Ajouter Billet</a>
            </li>
        </ul>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Information Billet</div>
                </div>
                <form action="{{ route('storeMyBillet') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="evenement">Événement<span class="tf-color-1">*</span></label>
                            <select class="form-control" id="evenement" required name="eve_id">
                                <option value="">Sélectionnez l'évènement</option>
                                @forelse ($event as $event)
                                <option value="{{ $event->id }}" {{ old('eve_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->nom }} : {{ $event->description }} (reste que {{ $event->placeRestant }} places à mettre en oeuvre)
                                @empty
                                <option disabled>Aucun évènement trouvé</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="typeBillet">Type de Billet<span class="tf-color-1">*</span></label>
                            <select class="form-control" required name="typ_id" id="typ_id" onchange="toggleNombreField()">
                                <option value="">Choisissez le type de billet</option>
                                @forelse ($type as $type)
                                <option value="{{ $type->id }}" data-nom="{{ $type->nom }}" {{ old('typ_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->nom }} : {{ $type->description }}
                                @empty
                                <option disabled>Aucun type de billet trouvé</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group" id="nombreField" style="display: none;">
                            <label for="nombre">Nombre<span class="tf-color-1">*</span></label>
                            <input type="number" class="form-control" id="nombre" name="nombre" placeholder="Nombre de place autorisé pour ce billet" oninput="validateInput()">
                        </div>
                        <div class="form-group">
                            <label for="prix">Prix<span class="tf-color-1">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">XOF</span>
                                </div>
                                <input type="number" class="form-control" aria-label="Amount (to the nearest dollar)" id="prix" name="prix" placeholder="Prix du billet" oninput="validateInput()" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quota">Quota<span class="tf-color-1">*</span></label>
                            <input type="number" class="form-control" id="quota" name="quota" placeholder="Quota du billet à mettre en place" oninput="validateInput()" required>
                        </div>
                    </div>
                    <div class="card-action text-right">
                        <button type="button" class="btn btn-danger" onclick="window.history.back()">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('typ_id');
        const nombreField = document.getElementById('nombreField');
        const nombreInput = document.getElementById('nombre');

        function toggleNombreField() {
            const selectedTypeOption = typeSelect.options[typeSelect.selectedIndex];
            const selectedTypeName = selectedTypeOption.textContent.trim().toLowerCase();

            if (selectedTypeName.includes('famille') || selectedTypeName.includes('groupe')) {
                nombreField.style.display = 'block';
                nombreInput.setAttribute('required', 'required');
            } else {
                nombreField.style.display = 'none';
                nombreInput.removeAttribute('required');
            }
        }

        typeSelect.addEventListener('change', toggleNombreField);
        toggleNombreField();
    });

    function validateInput() {
        const prixInput = document.getElementById('prix');
        prixInput.value = prixInput.value.replace(/[^0-9]/g, '');

        const quotaInput = document.getElementById('quota');
        quotaInput.value = quotaInput.value.replace(/[^0-9]/g, '');

        const nombreInput = document.getElementById('nombre');
        nombreInput.value = nombreInput.value.replace(/[^0-9]/g, '');
    }
</script>


@endsection

@extends('organizer.include.layouts.app')

@section('content')


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
                <a href="{{ route('myEvent') }}">Mes Evènements</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a>Editer Evènement</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Information Evènement</div>
                </div>
                <form action="{{ route('updateMyEvent', ['id' => $event->id]) }}" method="POST" enctype="multipart/form-data">
                    @METHOD('PUT')
                    @csrf
                    <div class="card-action">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="quota">Nombre de place<span class="tf-color-1">*</span></label>
                                    <input type="number" class="form-control" id="quota" name="place" placeholder="Nombre de place pour cet évènement" oninput="validateInput()" required value="{{ old('nom', $event->place) }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="quota">Catégorie d'évènement<span class="tf-color-1">*</span></label>
                                    <select class="form-control" id="exampleFormControlSelect2" required name="categorie_evenement_id">
                                        <option value="">Sélectionnez une catégorie</option>
                                        @forelse ($categories as $categorie)
                                        <option value="{{ $categorie->id }}" {{ $categorie->id == old('categorie_evenement_id', $event->cat_id) ? 'selected' : '' }}>{{ $categorie->nom }} : {{ $categorie->description }}</option>
                                        @empty
                                        <option disabled>Aucune catégorie d'évènement trouvée</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="nom">Nom<span class="tf-color-1">*</span></label>
                                    <input type="text" class="form-control" id="nom" placeholder="Nom de l'évènement" name="nom" value="{{ old('nom', $event->nom) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="lieu">Lieu<span class="tf-color-1">*</span></label>
                                    <input type="text" class="form-control" id="lieu" placeholder="Lieu de l'évènement" name="lieu" value="{{ old('lieu', $event->lieu) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description<span class="tf-color-1">*</span></label>
                                    <textarea class="form-control" id="description" rows="5" name="description" placeholder="Description de l'évènement" required>{{ old('description', $event->description) }}</textarea>
                                    <small id="descriptionHelp" class="form-text text-muted">Ne dépassez pas 200 caractères lors de la saisie de la description de l'évènement.</small>
                                </div>
                                <div class="form-group">
                                    <label for="heure">A partir de<span class="tf-color-1">*</span></label>
                                    <input type="text" class="form-control" id="timePicker" name="heure" required value="{{ old('heure', $event->heure) }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="dateDebut">Date debut<span class="tf-color-1">*</span></label>
                                    <input type="text" class="form-control" id="dateDebut" name="dateDebut" required value="{{ old('dateDebut', $event->dateDebut) }}">
                                </div>
                                <div class="form-group">
                                    <label for="dateFin">Date fin<span class="tf-color-1">*</span></label>
                                    <input type="text" class="form-control" id="dateFin" name="dateFin" required value="{{ old('dateDebut', $event->dateFin) }}">
                                </div>
                                <div class="form-group text-center">
                                    <div class="row justify-content-center">
                                        <div class="col-md-4">
                                            <label for="current_image">Affiche actuelle de l'évènement</label>
                                            <div>
                                                <img src="{{ $event->image ? asset($event->image) : '' }}" alt="Aucune affiche" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                                            <span>→</span>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="current_image">Nouvelle affiche de l'évènement</label>
                                            <div id="image-preview-link">
                                                <img id="image-preview-img" alt="Aucune affiche selectionnée" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image">Remplacez l'affiche de l'évènement ici</label>
                                    <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action text-right">
                        <button type="button" class="btn btn-danger" onclick="window.history.back()">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr('#timePicker', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });

        flatpickr('#dateDebut', {
            dateFormat: "d M Y",
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                const dateFinPicker = document.getElementById('dateFin')._flatpickr;
                dateFinPicker.set('minDate', dateStr);
            }
        });

        flatpickr('#dateFin', {
            dateFormat: "d M Y",
            required : true,
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                const dateDebutPicker = document.getElementById('dateDebut')._flatpickr;
                dateDebutPicker.set('maxDate', dateStr);
            }
        });

    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview-img').src = e.target.result;
                document.getElementById('image-preview-link').href = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

@endsection

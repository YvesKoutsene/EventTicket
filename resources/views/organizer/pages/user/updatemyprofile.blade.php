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
                <a href="{{ route('myProfile') }}">Mon Compte</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a>Editer Profil</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-6 ml-auto mr-auto">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Mettre à jour les informations de profil</div>
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('updateOrganizerInfo', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group text-center">
                            <div class="row justify-content-center">
                                <div class="col-md-4">
                                    <label for="current_image">Photo de profil actuelle</label>
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ $user->profile ? asset($user->profile) : '' }}" alt="Aucune photo de profil" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-center justify-content-center">
                                    <span>→</span>
                                </div>
                                <div class="col-md-4">
                                    <label for="current_image">Nouvelle photo de profil</label>
                                    <div id="image-preview-link" class="d-flex justify-content-center">
                                        <img id="image-preview-img" alt="Aucune photo sélectionnée" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile">Télécharger nouvelle photo de profil</label>
                            <input type="file" class="form-control @error('profile') is-invalid @enderror" id="profile" name="profile" accept="image/*" onchange="previewImage(event)">
                            @error('profile')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="number">Téléphone</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <select id="countryCode" class="select form-control" onchange="updateMaxLength()">
                                    <option value="+228" data-maxlength="8" {{ old('countryCode') == '+228' ? 'selected' : '' }}>Togo (+228)</option>
                                    <option value="+229" data-maxlength="8" {{ old('countryCode') == '+229' ? 'selected' : '' }}>Bénin (+229)</option>
                                    <option value="+226" data-maxlength="8" {{ old('countryCode') == '+226' ? 'selected' : '' }}>Burkina Faso (+226)</option>
                                    <option value="+233" data-maxlength="10" {{ old('countryCode') == '+233' ? 'selected' : '' }}>Ghana (+233)</option>
                                    <option value="+234" data-maxlength="10" {{ old('countryCode') == '+234' ? 'selected' : '' }}>Nigeria (+234)</option>
                                    <option value="+225" data-maxlength="8" {{ old('countryCode') == '+225' ? 'selected' : '' }}>Côte d'Ivoire (+225)</option>
                                    <option value="+243" data-maxlength="9" {{ old('countryCode') == '+243' ? 'selected' : '' }}>RD Congo (+243)</option>
                                    <option value="+27" data-maxlength="9" {{ old('countryCode') == '+27' ? 'selected' : '' }}>Afrique du Sud (+27)</option>
                                    <option value="+237" data-maxlength="9" {{ old('countryCode') == '+237' ? 'selected' : '' }}>Cameroun (+237)</option>
                                    <option value="+251" data-maxlength="9" {{ old('countryCode') == '+251' ? 'selected' : '' }}>Éthiopie (+251)</option>
                                    <option value="+254" data-maxlength="9" {{ old('countryCode') == '+254' ? 'selected' : '' }}>Kenya (+254)</option>
                                    <option value="+255" data-maxlength="9" {{ old('countryCode') == '+255' ? 'selected' : '' }}>Tanzanie (+255)</option>
                                    <option value="+256" data-maxlength="9" {{ old('countryCode') == '+256' ? 'selected' : '' }}>Ouganda (+256)</option>
                                </select>
                                <input type="text" id="phoneNumber" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ old('number', $user->number) }}" oninput="validateInput()" maxlength="8" required>
                                @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Mettre à jour le mot de passe</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('updateOrganizerPassword', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password">Mot de passe actuel</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-toggle="#current_password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Nouveau mot de passe</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-toggle="#password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-toggle="#password_confirmation">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateMaxLength() {
            var select = document.getElementById('countryCode');
            var phoneNumberInput = document.getElementById('phoneNumber');
            var selectedOption = select.options[select.selectedIndex];
            var maxLength = selectedOption.getAttribute('data-maxlength');
            phoneNumberInput.setAttribute('maxlength', maxLength);
            phoneNumberInput.value = phoneNumberInput.value.slice(0, maxLength);
        }

        function validateInput() {
            var phoneNumberInput = document.getElementById('phoneNumber');
            phoneNumberInput.value = phoneNumberInput.value.replace(/[^0-9]/g, '');
        }

        updateMaxLength();
        window.updateMaxLength = updateMaxLength;
        window.validateInput = validateInput;

        const togglePasswordButtons = document.querySelectorAll('.toggle-password');

        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = document.querySelector(button.getAttribute('data-toggle'));
                if (input.type === 'password') {
                    input.type = 'text';
                    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    button.innerHTML = '<i class="fa fa-eye"></i>';
                }
            });
        });

    });

    document.addEventListener('DOMContentLoaded', (event) => {
        updateMaxLength();
    });

    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image-preview-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<style>
    .number {
        margin-bottom: 24px;
    }
    .body-title {
        margin-bottom: 10px;
    }
    .flex-grow {
        flex-grow: 1;
    }
    .indicator-select {
        margin-right: 10px;
    }
</style>
@endsection

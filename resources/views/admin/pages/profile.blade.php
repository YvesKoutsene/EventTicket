@extends('admin.include.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Profil </h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Profil</div>
        </li>
    </ul>
</div>

<form class="form-add-new-user form-style-2" method="POST" action="{{ route('updateAdminInfo', $user->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="wg-box">
        <div class="left">
            <h5 class="mb-4">Information Compte</h5>
            <div class="body-text">Mettez à jour vos informations de compte</div>
        </div>
        <div class="right flex-grow">
            <fieldset class="mb-24">
                <div class="col-12 mb-20">
                    <div class="upload-image mb-16">
                        <div class="item">
                            <a value="" id="image-preview-link">
                                <img id="image-preview-img" src="{{ $user->profile ? asset($user->profile) : '' }}" alt="Aperçu du profil" style="max-width: 100%; max-height: 200px;">
                            </a>
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                  <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Téléversez une photo de profil à partir <span class="tf-color">d'ici</span></span>
                                <input type="file" id="myFile" name="profile" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="name mb-24">
                <div class="body-title mb-10">Nom<span class="tf-color-1">*</span></div>
                <input class="flex-grow" type="text" placeholder="Nom d'utilisateur" name="name" tabindex="0" value="{{ old('name', $user->name) }}" aria-required="true" required>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </fieldset>
            <fieldset class="email mb-24">
                <div class="body-title mb-10">Email<span class="tf-color-1">*</span></div>
                <input class="flex-grow" type="email" placeholder="Email" name="email" tabindex="0" value="{{ old('email', $user->email) }}" aria-required="true" required>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </fieldset>
            <fieldset class="number mb-24">
                <div class="body-title mb-10">Téléphone<span class="tf-color-1">*</span></div>
                <div style="display: flex; align-items: center;">
                    <select id="countryCode" class="indicator-select" onchange="updateMaxLength()">
                        <option value="+228" data-maxlength="8">Togo (+228)</option>
                        <option value="+229" data-maxlength="8">Bénin (+229)</option>
                        <option value="+226" data-maxlength="8">Burkina Faso (+226)</option>
                        <option value="+233" data-maxlength="10">Ghana (+233)</option>
                        <option value="+234" data-maxlength="10">Nigeria (+234)</option>
                        <option value="+225" data-maxlength="8">Côte d'Ivoire (+225)</option>
                        <option value="+243" data-maxlength="9">RD Congo (+243)</option>
                        <option value="+27" data-maxlength="9">Afrique du Sud (+27)</option>
                        <option value="+237" data-maxlength="9">Cameroun (+237)</option>
                        <option value="+251" data-maxlength="9">Éthiopie (+251)</option>
                        <option value="+254" data-maxlength="9">Kenya (+254)</option>
                        <option value="+255" data-maxlength="9">Tanzanie (+255)</option>
                        <option value="+256" data-maxlength="9">Ouganda (+256)</option>
                    </select>
                    <input id="phoneNumber" class="flex-grow" type="text" placeholder="Numéro de téléphone" name="number" tabindex="0" value="{{ old('number', $user->number) }}" aria-required="true" required oninput="validateInput()" maxlength="8">
                    <x-input-error class="mt-2" :messages="$errors->get('number')" />
                </div>
            </fieldset>
            <div class="bot">
                <div></div>
                <button class="tf-button w180" type="submit">Modifier</button>
            </div>
        </div>
    </div>
</form>

<form class="form-add-new-user form-style-2 mt-4" method="POST" action="{{ route('updateAdminPassword', $user->id) }}">
    @csrf
    @method('PUT')
    <div class="wg-box">
        <div class="left">
            <h5 class="mb-4">Mot de passe</h5>
            <div class="body-text">S'assurer d'avoir un mot de passe robuste et long (minimum 8 caractères)</div>
        </div>
        <div class="right flex-grow">
            <fieldset class="password mb-24">
                <div class="body-title mb-10">Mot de passe actuel<span class="tf-color-1">*</span></div>
                <input class="password-input" type="password" placeholder="Entrer le mot de passe actuel" name="current_password" tabindex="0" aria-required="true" required>
                <span class="show-pass">
                    <i class="icon-eye view"></i>
                    <i class="icon-eye-off hide"></i>
                </span>
                <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
            </fieldset>
            <fieldset class="password mb-24">
                <div class="body-title mb-10">Nouveau mot de passe<span class="tf-color-1">*</span></div>
                <input class="password-input" type="password" placeholder="Entrer un nouveau mot de passe" name="password" tabindex="0" aria-required="true" required>
                <span class="show-pass">
                    <i class="icon-eye view"></i>
                    <i class="icon-eye-off hide"></i>
                </span>
                <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
            </fieldset>
            <fieldset class="password mb-24">
                <div class="body-title mb-10">Confirmation<span class="tf-color-1">*</span></div>
                <input class="password-input" type="password" placeholder="Confirmer le nouveau mot de passe" name="password_confirmation" tabindex="0" aria-required="true" required>
                <span class="show-pass">
                    <i class="icon-eye view"></i>
                    <i class="icon-eye-off hide"></i>
                </span>
                <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
            </fieldset>
            <div class="bot">
                <div></div>
                <button class="tf-button w180" type="submit">Modifier</button>
            </div>
        </div>
    </div>
</form>

<script>
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
    });

    document.addEventListener('DOMContentLoaded', (event) => {
        updateMaxLength();
    });
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

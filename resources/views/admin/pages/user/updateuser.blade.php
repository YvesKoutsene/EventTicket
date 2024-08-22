@extends('admin.include.layouts.app')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Information Utilisateur</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="{{ route('dashboard') }}">
                <div class="text-tiny">Dashboard</div>
            </a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <a href="{{ route('user') }}">
                <div class="text-tiny">Utilisateurs</div>
            </a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Modifier Utilisateur</div>
        </li>
    </ul>
</div>

<form class="form-add-new-user form-style-2" method="POST" action="{{ route('updateUser', $user->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="wg-box">
        <div class="left">
            <h5 class="mb-4">Compte</h5>
            <div class="body-text">Veuillez laisser ou changer les informations du compte</div>
        </div>
        <div class="right flex-grow">
            <fieldset class="name mb-24">
                <div class="body-title mb-10">Nom<span class="tf-color-1">*</span></div>
                <input class="flex-grow" type="text" placeholder="Nom d'utilisateur" name="name" tabindex="0" value="{{ old('name', $user->name) }}" aria-required="true" required>
            </fieldset>
            <fieldset class="email mb-24">
                <div class="body-title mb-10">Email<span class="tf-color-1">*</span></div>
                <input class="flex-grow" type="email" placeholder="Email" name="email" tabindex="0" value="{{ old('email', $user->email) }}" aria-required="true" required>
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
                </div>
            </fieldset>

            <fieldset class="password mb-24">
                <div class="body-title mb-10">Nouveau mot de passe</div>
                <input class="password-input" type="password" placeholder="Entrer un nouveau mot de passe (laisser vide pour conserver l'actuel)" name="password" tabindex="0" aria-required="false">
                <span class="show-pass">
                    <i class="icon-eye view"></i>
                    <i class="icon-eye-off hide"></i>
                </span>
            </fieldset>
            <fieldset class="password">
                <div class="body-title mb-10">Confirmation</div>
                <input class="password-input" type="password" placeholder="Confirmer le nouveau mot de passe" name="password_confirmation" tabindex="0" aria-required="false">
                <span class="show-pass">
                    <i class="icon-eye view"></i>
                    <i class="icon-eye-off hide"></i>
                </span>
            </fieldset>
        </div>
    </div>

    <div class="wg-box">
        <div class="left">
            <h5 class="mb-4">Profil</h5>
            <div class="body-text">Téléverser ou conserver la photo de profil</div>
        </div>
        <div class="right flex-grow">
            <fieldset class="mb-24">
                <div class="col-12 mb-20">
                    <div class="upload-image mb-16">
                        <div class="item">
                            <a value="" id="image-preview-link">
                                <img id="image-preview-img" src="{{ $user->profile ? asset($user->profile) : '' }}" alt="Aperçu de l'image" style="max-width: 100%; max-height: 200px;">
                            </a>
                        </div>
                        <div class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                  <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Téléversez une image à partir <span class="tf-color">d'ici</span></span>
                                <input type="file" id="myFile" name="profile" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                    </div>
                </div>
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

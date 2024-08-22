<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<!--<![endif]-->

<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>EventTicket Login Page</title>

    <meta name="author" content="themesflat.com">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">

    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('assets/font/fonts.css') }}">

    <!-- Icon -->
    <link rel="stylesheet" href="{{ asset('assets/icon/style.css') }}">

    <!-- Favicon and Touch Icons  -->
    <!--
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/images/favicon.png') }}">
    -->

    <!-- CSS for intl-tel-input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>

    <!-- JS for intl-tel-input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>


</head>

<body class="body">

<!-- #wrapper -->
<div id="wrapper">
    <!-- #page -->
    @include('admin.include.partials.message')
    <div id="page" class="">
        <div class="wrap-login-page">
            <div class="flex-grow flex flex-column justify-center gap30">
                <a href="" id="site-logo-inner">

                </a>
                <div class="login-box">
                    <div>
                        <h3>Créez votre compte</h3>
                        <div class="body-text">Entrez vos informations personnelles pour créer un compte</div>
                    </div>
                    <form class="form-login flex flex-column gap24" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" >
                        @csrf
                        <fieldset class="name">
                            <div class="body-title mb-10">Photo de profil</div>
                            <div class="col-12 mb-20">
                                <div class="upload-image mb-16">
                                    <div class="item">
                                        <a value="" id="image-preview-link">
                                            <img id="image-preview-img" src="" alt="Aperçu de l'image" style="max-width: 100%; max-height: 200px;">
                                        </a>
                                    </div>
                                    <div class="item up-load">
                                        <label class="uploadfile" for="myFile">
                                            <span class="icon">
                                              <i class="icon-upload-cloud"></i>
                                            </span>
                                            <span class="text-tiny">Téléversez votre photo à partir <span class="tf-color">d'ici</span></span>
                                            <input type="file" id="myFile" name="profile" accept="image/*" onchange="previewImage(this)" class="custom-file-input @error('profile') is-invalid @enderror">
                                        </label>
                                    </div>
                                    @error('profile')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Nom <span class="tf-color-1">*</span></div>
                            <input id="nom" type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autocomplete="name" value="{{ old('name') }}" autofocus placeholder="Tapez votre nom complet">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </fieldset>
                        <fieldset class="email">
                            <div class="body-title mb-10">Adresse Email <span class="tf-color-1">*</span></div>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="{{ old('email') }}" autofocus placeholder="Tapez votre adresse email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Téléphone<span class="tf-color-1">*</span></div>
                            <div style="display: flex; align-items: center;">
                                <select id="countryCode" class="indicator-select" onchange="updateMaxLength()">
                                    <option value="+228" data-maxlength="8">Togo (+228)</option>
                                    <option value="+229" data-maxlength="8">Benin(+229)</option>
                                    <option value="+226" data-maxlength="8">Burkina Faso(+226)</option>
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
                                <input id="phoneNumber" class="flex-grow" class="form-control @error('number') is-invalid @enderror" type="text" placeholder="Tapez votre numero de téléphone" name="number" tabindex="0" aria-required="true" required oninput="validateInput()" maxlength="8">
                                @error('number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </fieldset>
                        <fieldset class="password">
                            <div class="body-title mb-10">Mot de passe <span class="tf-color-1">*</span></div>
                            <input class="password-input password-input @error('password') is-invalid @enderror" type="password" placeholder="Tapez votre mot de passe" name="password" value="{{ old('Password') }}" tabindex="0" aria-required="true" required="" id="password">
                            <span class="show-pass">
                                    <i class="icon-eye view"></i>
                                    <i class="icon-eye-off hide"></i>
                                </span>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </fieldset>
                        <fieldset class="password">
                            <div class="body-title mb-10">Confirmation<span class="tf-color-1">*</span></div>
                            <input class="password-input password-input @error('password_confirmation') is-invalid @enderror" type="password" placeholder="Confirmer votre mot de passe" name="password_confirmation" value="{{ old('Confirm Password') }}" tabindex="0" aria-required="true" required="" id="password">
                            <span class="show-pass">
                                    <i class="icon-eye view"></i>
                                    <i class="icon-eye-off hide"></i>
                                </span>
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </fieldset>
                        <button type="submit" class="tf-button w-full">S'inscrire</button>
                        <div class="body-text text-center">
                            Vous avez un compte ?
                            <a href="{{ route('login') }}" class="body-text tf-color">Connexion</a>
                        </div>
                    </form>

                </div>
            </div>
            <div class="text-tiny">
                Copyright © 2024 <a href="https://www.instagram.com/carlos_arttg?igsh=ZWdiam83dHp0anZh">Carlos's Conception</a>,
                All rights reserved.
            </div>
        </div>
    </div>
    <!-- /#page -->
</div>
<!-- /#wrapper -->

<!-- Javascript -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

</body>


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

    function updateMaxLength() {
        const select = document.getElementById('countryCode');
        const input = document.getElementById('phoneNumber');
        const maxLength = select.options[select.selectedIndex].getAttribute('data-maxlength');
        input.maxLength = maxLength;
        input.value = '';
    }

    function validateInput() {
        const input = document.getElementById('phoneNumber');
        input.value = input.value.replace(/[^0-9]/g, '');
    }

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


</html>


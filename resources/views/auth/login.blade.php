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
                        <h3>Connexion au compte</h3>
                        <div class="body-text">Entrez votre email et votre mot de passe pour vous connecter</div>
                    </div>
                    <form class="form-login flex flex-column gap24" method="POST" action="{{ route('login') }}">
                        @csrf
                        <fieldset class="email">
                            <div class="body-title mb-10">Adresse Email <span class="tf-color-1">*</span></div>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Tapez votre adresse email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </fieldset>
                        <fieldset class="password">
                            <div class="body-title mb-10">Mot de passe <span class="tf-color-1">*</span></div>
                            <input class="password-input password-input @error('password') is-invalid @enderror" type="password" placeholder="Tapez votre mot de passe" name="password" tabindex="0" value="" aria-required="true" required="" id="password">
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
                        <div class="flex justify-between items-center">
                            <div class="flex gap10">
                                <input class="" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} >
                                <label class="body-text" for="remember">Gardez-moi connecté</label>
                            </div>

                                @if (Route::has('password.request'))
                                <a class="body-text tf-color" href="{{ route('password.request') }}">
                                    Mot de passe oublié?
                                </a>
                                @endif

                        </div>
                        <button type="submit" class="tf-button w-full">Se connecter</button>
                        <div class="body-text text-center">
                            Vous n'avez pas encore de compte ?
                            <a href="{{ route('register') }}" class="body-text tf-color">Inscription</a>
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

</html>
@extends('organizer.include.layouts.app')
@section('content')
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
                <a>Scanner Ticket</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card card-secondary">
                <div class="card-body skew-shadow">
                    <h1>3,072</h1>
                    <h5 class="op-8">Nombre de ticket Scanné</h5>
                    <div class="pull-right">
                        <h3 class="fw-bold op-8">Ajourd'hui</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card card-secondary text-center">
                <div class="card-body">
                    <button id="startScanButton" class="btn btn-primary">
                        <i class="fas fa-camera"></i> Démarrer le Scan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <video id="video" width="300" height="200" autoplay></video>
        <div id="scanResult" class="mt-3"></div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
    let html5QrCode;

    document.getElementById('startScanButton').addEventListener('click', function() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(stream => {
                const video = document.getElementById('video');
                video.srcObject = stream;

                // Initialiser le QR code scanner
                html5QrCode = new Html5Qrcode("scanResult");

                // Démarrer le scan
                html5QrCode.start(
                    { facingMode: "environment" }, // Utiliser la caméra arrière
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        document.getElementById('scanResult').innerText = `Ticket Scanné: ${decodedText}`;
                        // Traitement du ticket scanné ici
                        html5QrCode.stop(); // Arrêter le scan
                        // Optionnel : arrêter le flux vidéo
                        stream.getTracks().forEach(track => track.stop());
                    },
                    (errorMessage) => {
                        // Gérer les erreurs ici si nécessaire
                    }
                ).catch(err => {
                    console.error(`Erreur lors du démarrage de la caméra: ${err}`);
                    alert("Erreur d'accès à la caméra.");
                });
            })
            .catch(err => {
                console.error(`Erreur d'accès à la caméra: ${err}`);
                alert("Erreur d'accès à la caméra.");
            });
    });
</script>
@endsection

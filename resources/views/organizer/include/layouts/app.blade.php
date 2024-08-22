<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'EventTicket Organizer Dashboard')</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="" href="\assets/images/logo/Img.jpg" type="image/x-icon"/>

    <!-- Fonts and icons -->
    <script src="\assetsor/js/plugin/webfont/webfont.min.js"></script>
    <!--Here-->
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ["{{ asset('\assetsor/css/fonts.min.css') }}"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <link rel="stylesheet" href="\assetsor/css/bootstrap.min.css">
    <link rel="stylesheet" href="\assetsor/css/atlantis.min.css">
    <link rel="stylesheet" href="\assetsor/css/demo.css">

</head>
<body>
<div class="wrapper">
    <div class="main-header">
        <div class="logo-header" data-background-color="blue">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="\assets/images/logo/Img.jpg" alt="navbar brand" class="logo-image" >
                <span class="logo-text">EventTicket</span>
            </a>
            <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
            </button>
            <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="icon-menu"></i>
                </button>
            </div>
        </div>

        <!--Header-->
        <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
            @include('organizer.include.partials.header')
        </nav>
    </div>

    <!--Sidebar-->
    <div class="sidebar sidebar-style-2">
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                @include('organizer.include.partials.sidebar')
            </div>
        </div>
    </div>

    <!--Main-->
    <div class="main-panel">
        <div class="content">
            @yield('content')
        </div>

        <!--Footer-->
        <footer class="footer">
            <div class="container-fluid">
                @include('organizer.include.partials.footer')
            </div>
        </footer>

        <div class="custom-template">
            @include('organizer.include.partials.custom')
        </div>

    </div>
</div>

<script src="\assetsor/js/core/jquery.3.2.1.min.js"></script>
<script src="\assetsor/js/core/popper.min.js"></script>
<script src="\assetsor/js/core/bootstrap.min.js"></script>

<script src="\assetsor/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="\assetsor/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<script src="\assetsor/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<script src="\assetsor/js/plugin/chart.js/chart.min.js"></script>
<!--
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
-->
<script src="\assetsor/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<script src="\assetsor/js/plugin/chart-circle/circles.min.js"></script>

<script src="\assetsor/js/plugin/datatables/datatables.min.js"></script>

<script src="\assetsor/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<script src="\assetsor/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="\assetsor/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<script src="\assetsor/js/plugin/sweetalert/sweetalert.min.js"></script>

<script src="\assetsor/js/atlantis.min.js"></script>

<script src="\assetsor/js/setting-demo.js"></script>
<!--Here-->
<script src="\assetsor/js/demo.js"></script>

<!--By Jean-yves-->
@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Evenement;

    $userId = Auth::id();

    $rejectedCount = Evenement::where('user_id', $userId)->where('status', 'rejeté')->count();
    $activeCount = Evenement::where('user_id', $userId)->where('status', 'actif')->count();
    $closedCount = Evenement::where('user_id', $userId)->where('status', 'fermé')->count();
    $finishedCount = Evenement::where('user_id', $userId)->where('status', 'terminé')->count();

    $allZero = ($rejectedCount == 0 && $activeCount == 0 && $closedCount == 0 && $finishedCount == 0);

@endphp
<script>
    var doughnutChart = document.getElementById('doughnutChart').getContext('2d');

    @if ($allZero)
        var myDoughnutChart = new Chart(doughnutChart, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [1],
                    backgroundColor: ['#cccccc']
                }],
                labels: ['Aucune donnée']
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                    labels: {
                        fontColor: '#333',
                        fontSize: 14
                    }
                },
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    @else
    var myDoughnutChart = new Chart(doughnutChart, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    //{{ $rejectedCount }},
                {{ $activeCount }},
    {{ $closedCount }},
    {{ $finishedCount }}
    ],
    backgroundColor: [/*'#f3545d',*/ '#007bff', '#28a745', '#ffc107'],
        borderColor: '#fff',
        borderWidth: 2
    }],
    labels: [
        //'Rejeté',
        'Actif',
        'Fermé',
        'Terminé'
    ]
    },
    options: {
        responsive: true,
            maintainAspectRatio: false,
            legend: {
            position: 'bottom',
                labels: {
                fontColor: '#333',
                    fontSize: 14
            }
        },
        layout: {
            padding: {
                left: 20,
                    right: 20,
                    top: 20,
                    bottom: 20
            }
        },
        animation: {
            animateScale: true,
                animateRotate: true
        },
        tooltips: {
            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                titleFontColor: '#fff',
                bodyFontColor: '#fff',
                borderColor: '#f3545d',
                borderWidth: 1,
                xPadding: 10,
                yPadding: 10
        }
    }
    });
    @endif
</script>

@php
    use App\Models\Avis;
    use App\Models\FactureCommande;
    use App\Models\Billet;

    $userId = Auth::id();

    $evenements = Evenement::where('user_id', $userId)->get();

    $avis = array_fill(0, 12, 0);
    $commande = array_fill(0, 12, 0);
    $revenu = array_fill(0, 12, 0);

    for ($month = 1; $month <= 12; $month++) {
    $avis[$month - 1] = Avis::whereIn('eve_id', $evenements->pluck('id'))
    ->whereMonth('created_at', $month)
    ->count();

    $factureCommandes = FactureCommande::whereIn('bil_id',
    Billet::whereIn('eve_id', $evenements->pluck('id'))->pluck('id'))
    ->whereMonth('created_at', $month)
    ->get();

    $commande[$month - 1] = $factureCommandes->count();
    $revenu[$month - 1] = $factureCommandes->sum('prixTotal');
    }
@endphp

<script>
    $(document).ready(function() {
        function initializeChart() {
            var ctx = document.getElementById('statisticsChart').getContext('2d');

            var totalAvis = @json(array_sum($avis));
            var totalCommande = @json(array_sum($commande));
            var totalRevenu = @json(array_sum($revenu));

            if (totalAvis === 0 && totalCommande === 0 && totalRevenu === 0) {
                $('#noDataMessage').show().css({
                    'font-size': '18px',
                    'color': '#f3545d',
                    'text-align': 'center',
                    'margin-top': '20px'
                }).text('Aucune donnée');
                $('#statisticsChart').show();
            } else {
                $('#noDataMessage').hide();
                $('#statisticsChart').show();
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "Avis",
                        borderColor: '#f3545d',
                        pointBackgroundColor: 'rgba(243, 84, 93, 0.6)',
                        pointRadius: 3,
                        backgroundColor: 'rgba(243, 84, 93, 0.4)',
                        yAxisID: 'y-axis-1',
                        fill: true,
                        borderWidth: 2,
                        data: @json($avis)
                    }, {
                        label: "Commande",
                        borderColor: '#fdaf4b',
                        pointBackgroundColor: 'rgba(253, 175, 75, 0.6)',
                        pointRadius: 3,
                        backgroundColor: 'rgba(253, 175, 75, 0.4)',
                        yAxisID: 'y-axis-2',
                        fill: true,
                        borderWidth: 2,
                        data: @json($commande)
                    }, {
                        label: "Revenu",
                        borderColor: '#177dff',
                        pointBackgroundColor: 'rgba(23, 125, 255, 0.6)',
                        pointRadius: 3,
                        backgroundColor: 'rgba(23, 125, 255, 0.4)',
                        yAxisID: 'y-axis-3',
                        fill: true,
                        borderWidth: 2,
                        data: @json($revenu)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        labels: {
                            fontColor: '#333',
                            fontSize: 14
                        }
                    },
                    tooltips: {
                        mode: "index",
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFontColor: '#fff',
                        bodyFontColor: '#fff',
                        borderColor: '#f3545d',
                        borderWidth: 1,
                        xPadding: 10,
                        yPadding: 10
                    },
                    layout: {
                        padding: { left: 10, right: 10, top: 15, bottom: 15 }
                    },
                    scales: {
                        yAxes: [{
                            id: 'y-axis-1',
                            position: 'left',
                            ticks: {
                                fontStyle: "500",
                                beginAtZero: false,
                                maxTicksLimit: 5,
                                padding: 10
                            },
                            gridLines: {
                                drawTicks: false,
                                display: true
                            }
                        }, {
                            id: 'y-axis-2',
                            position: 'right',
                            ticks: {
                                fontStyle: "500",
                                beginAtZero: false,
                                maxTicksLimit: 5,
                                padding: 10
                            },
                            gridLines: {
                                drawTicks: false,
                                display: true
                            }
                        }, {
                            id: 'y-axis-3',
                            position: 'right',
                            ticks: {
                                fontStyle: "500",
                                beginAtZero: false,
                                maxTicksLimit: 5,
                                padding: 10
                            },
                            gridLines: {
                                drawTicks: false,
                                display: true
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                zeroLineColor: "transparent"
                            },
                            ticks: {
                                padding: 10,
                                fontStyle: "500"
                            }
                        }]
                    }
                }
            });
        }

        initializeChart();
    });
</script>

<style>
    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo-link {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .logo-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 8px;
    }

    .logo-text {
        font-size: 15px;
        font-weight: bold;
        color: ghostwhite;
        line-height: 3.5;
    }
</style>

<script >

    //Fonction de pagination et recherche java script
    $(document).ready(function() {
        $('#add-row').DataTable({
            "pageLength": 5,
        });

        var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $('#addRowButton').click(function() {
            $('#add-row').dataTable().fnAddData([
                $("#addName").val(),
                $("#addPosition").val(),
                $("#addOffice").val(),
                action
            ]);
            $('#addRowModal').modal('hide');
        });
    });

    $('#alert_demo_3').click(function(e) {
        swal("Oops!", "Bientôt disponible", {
            icon : "info",
            buttons: {
                confirm: {
                    className : 'btn btn-primary'
                }
            },
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.alert-delete-event').forEach(function(button) {
            button.addEventListener('click', function() {
                var eventId = this.getAttribute('data-event-id');
                swal({
                    title: 'Êtes-vous sûr?',
                    text: "Cela supprimera également les billets de cet événement",
                    type: 'warning',
                    buttons: {
                        cancel: {
                            visible: true,
                            text: 'Non, annulez!',
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: 'Oui, supprimez-le!',
                            className: 'btn btn-success'
                        }
                    }
                }).then((willDelete) => {
                    if (willDelete) {
                        var deleteForm = document.getElementById('delete-event-form-' + eventId);
                        deleteForm.submit();
                    } else {
                        swal("Votre évènement est en sûreté!", {
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            }
                        });
                    }
                });
            });
        });
    });

    //Alerte d'annulation d'évènement
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.alert-canceled-event').forEach(function(button) {
            button.addEventListener('click', function() {
                var eventId = this.getAttribute('data-canceled-event-id');
                swal({
                    title: 'Êtes-vous sûr?',
                    text: "Cela annulera également les billets de cet événement",
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            visible: true,
                            text: 'Non, retour!',
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: 'Oui, annulez-le!',
                            className: 'btn btn-success'
                        }
                    }
                }).then((willDelete) => {
                    if (willDelete) {
                        var deleteForm = document.getElementById('delete-event-form-' + eventId);
                        deleteForm.submit();
                    } else {
                        swal("Votre événement est en sécurité!", {
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            }
                        });
                    }
                });
            });
        });
    });

    //Fonction alerte d'annulation de billet
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.alert-delete-event').forEach(function(button) {
            button.addEventListener('click', function() {
                var eventId = this.getAttribute('data-event02-id');
                swal({
                    title: 'Êtes-vous sûr?',
                    text: "Cela annulera également les billets de cet événement",
                    type: 'warning',
                    buttons: {
                        cancel: {
                            visible: true,
                            text: 'Non',
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: 'Oui, annulez-le!',
                            className: 'btn btn-success'
                        }
                    }
                }).then((willDelete) => {
                    if (willDelete) {
                        var deleteForm = document.getElementById('delete-event-form-' + eventId);
                        deleteForm.submit();
                    } else {
                        swal("Votre évènement est en sûreté!", {
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            }
                        });
                    }
                });
            });
        });
    });

    //Fonction alerte de suppression de billet
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.alert-delete-billet').forEach(function(button) {
            button.addEventListener('click', function() {
                var billetId = this.getAttribute('data-billet-id');
                swal({
                    title: 'Êtes-vous sûr?',
                    text: "Vous ne pourrez pas revenir en arrière!",
                    type: 'warning',
                    buttons: {
                        cancel: {
                            visible: true,
                            text: 'Non, annulez!',
                            className: 'btn btn-danger'
                        },
                        confirm: {
                            text: 'Oui, supprimez-le!',
                            className: 'btn btn-success'
                        }
                    }
                }).then((willDelete) => {
                    if (willDelete) {
                        var deleteForm = document.getElementById('delete-billet-form-' + billetId);
                        deleteForm.submit();
                    } else {
                        swal("Votre billet est en sûreté!", {
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            }
                        });
                    }
                });
            });
        });
    });

</script>

<!--By Jean-Yves-->
<!--Notification message personnalisé-->
@if (session('success') || session('error') || $errors->any())
    @php
        $errorMessages = $errors->all();
    @endphp
    <script>
        $(document).ready(function() {
            // Notify success message
            var successMessage = "{{ session('success') }}";
            if (successMessage) {
                $.notify({
                    icon: 'flaticon-alarm-1',
                    title: 'EventTicket',
                    message: successMessage
                }, {
                    type: 'success',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 8000
                });
            }

            var errorMessage = "{{ session('error') }}";
            if (errorMessage) {
                $.notify({
                    icon: 'flaticon-alarm-1',
                    title: 'EventTicket',
                    message: errorMessage
                }, {
                    type: 'danger',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 8000
                });
            }

            var errors = @json($errorMessages);
            errors.forEach(function(error) {
                $.notify({
                    icon: 'flaticon-alarm-1',
                    title: 'EventTicket',
                    message: error
                }, {
                    type: 'danger',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 8000
                });
            });

            /*if (!successMessage && !errorMessage) {
                $.notify({
                    icon: 'flaticon-alarm-1',
                    title: 'EventTicket',
                    message: 'Soyez les bienvenus parmi nous !'
                }, {
                    type: 'info',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    time: 6000
                });
            }*/

        });
    </script>
@endif

</body>

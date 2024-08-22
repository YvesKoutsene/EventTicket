<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'EventTicket Admin Dashboard')</title>
    <link rel="stylesheet" href="assets/styles.css">
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="author" content="themesflat.com">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="\assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="\assets/css/animation.css">
    <link rel="stylesheet" type="text/css" href="\assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="\assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="\assets/css/style.css">
    <!-- Font -->
    <link rel="stylesheet" href="\assets/font/fonts.css">
    <!-- Icon -->
    <link rel="stylesheet" href="\assets/icon/style.css">
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" src="\assets/images/logo/Img.jpg">
    <link rel="apple-touch-icon-precomposed" src="\assets/images/logo/Img.jpg">
</head>
<body class="body">
    <!-- #wrapper -->
    <div id="wrapper">
        <!-- #page -->
        <div id="page" class="">
            <!-- layout-wrap -->
            <div class="layout-wrap">
                <!-- preload -->
                <div id="preload" class="preload-container">
                    <div class="preloading">
                        <span></span>
                    </div>
                </div>
                <!-- /preload -->
                <!-- section-menu-left -->
                <div class="section-menu-left">
                    @include('admin.include.partials.sidebar')
                </div>
                <!-- /section-menu-left -->
                <!-- section-content-right -->
                <div class="section-content-right">
                    <!-- header-dashboard -->
                    <div class="header-dashboard">
                        @include('admin.include.partials.header')
                    </div>
                    <!-- /header-dashboard -->
                    <!-- main-content -->
                    <div class="main-content">
                        <!-- main-content-wrap -->
                        <div class="main-content-inner">
                            <!-- main-content-wrap -->
                            <div class="main-content-wrap">
                                @include('admin.include.partials.message')
                                @yield('content')
                            </div>
                            <!-- /main-content-wrap -->
                        </div>
                        <!-- /main-content-wrap -->
                        <!-- bottom-page -->
                        <div class="bottom-page">
                            @include('admin.include.partials.footer')
                        </div>
                        <!-- /bottom-page -->
                    </div>
                    <!-- /main-content -->
                </div>
                <!-- /section-content-right -->
            </div>
            <!-- /layout-wrap -->
        </div>
        <!-- /#page -->
    </div>
    <!-- /#wrapper -->

    <!-- Javascript -->
    <script src="\assets/js/jquery.min.js"></script>
    <script src="\assets/js/bootstrap.min.js"></script>
    <script src="\assets/js/bootstrap-select.min.js"></script>
    <script src="\assets/js/zoom.js"></script>
    <script src="\assets/js/jvectormap-1.2.2.min.js"></script>
    <script src="\assets/js/jvectormap-us-lcc.js"></script>
    <script src="\assets/js/jvectormap.js"></script>
    <script src="\assets/js/apexcharts/apexcharts.js"></script>
    <script src="\assets/js/apexcharts/line-chart-1.js"></script>
    <script src="\assets/js/apexcharts/line-chart-2.js"></script>
    <script src="\assets/js/apexcharts/line-chart-3.js"></script>
    <script src="\assets/js/apexcharts/line-chart-4.js"></script>
    <!--Le 7 est pour mon dash-->
    <script src="\assets/js/apexcharts/line-chart-7.js"></script>
    <script src="\assets/js/apexcharts/line-chart-9.js"></script>
     <script src="\assets/js/switcher.js"></script>
    <script src="\assets/js/theme-settings.js"></script>
    <script src="\assets/js/main.js"></script>

    <!-- les 3 formes le donut chart-->
    <script src="\assets/js/morris.min.js"></script>
    <script src="\assets/js/raphael.min.js"></script>
    <script src="\assets/js/morris.js"></script>

    @php
    // Calculer les données pour le graphique
    $monthlyData = [
    'users' => [],
    'events' => [],
    'orders' => [],
    'revenue' => []
    ];

    for ($month = 1; $month <= 12; $month++) {
    $monthlyData['users'][] = \App\Models\User::whereMonth('created_at', $month)
    ->where('role', '<>', 'admin')->count();
    $monthlyData['events'][] = \App\Models\Evenement::whereMonth('created_at', $month)->count();
    $monthlyData['orders'][] = \App\Models\FactureCommande::whereMonth('created_at', $month)->count();
    $monthlyData['revenue'][] = \App\Models\FactureCommande::whereMonth('created_at', $month)->sum('prixTotal');
    }
    @endphp

    <script>
        (function ($) {
            var tfLineChart = (function () {
                var chartBar = function () {
                    var data = @json($monthlyData);

                    var options = {
                        series: [{
                            name: 'Utilisateur',
                            data: data.users
                        }, {
                            name: 'Evènement',
                            data: data.events
                        }],
                        chart: {
                            height: 523,
                            type: 'area',
                            toolbar: {
                                show: false,
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false,
                        },
                        colors: ['#8F77F3', '#FF5200'],
                        stroke: {
                            curve: 'smooth'
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: '#95989D',
                                },
                            },
                            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
                        },
                        responsive: [{
                            breakpoint: 991,
                            options: {
                                chart: {
                                    height: 400
                                },
                            }
                        }],
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#95989D',
                                },
                            },
                        },
                        tooltip: {
                            x: {
                                format: 'dd/mm/yy'
                            },
                        },
                    };

                    var chart = new ApexCharts(
                        document.querySelector("#line-chart-7"),
                        options
                    );
                    if ($("#line-chart-7").length > 0) {
                        chart.render();
                    }
                };

                return {
                    init: function () {},

                    load: function () {
                        chartBar();
                    },
                    resize: function () {},
                };
            })();

            jQuery(document).ready(function () {});

            jQuery(window).on("load", function () {
                tfLineChart.load();
            });

            jQuery(window).on("resize", function () {});
        })(jQuery);
    </script>



</body>
</html>

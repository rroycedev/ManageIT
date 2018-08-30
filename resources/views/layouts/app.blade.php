<!doctype html>
<html lang="en">

    <head>
        <!-- Start of laravel stuff -->

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->

        <script src="{{ asset('js/app.js') }}"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito"
            rel="stylesheet" type="text/css">

        <!--  Start of material template stuff -->

        <link rel="apple-touch-icon" sizes="76x76"
            href="/assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="/images/favicon.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0,
            user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css"
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"
            />
        <link rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link href="/css/material-dashboard.css" rel="stylesheet"
            />

        <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
        <script>
            var messages = [];

            function rebuild_notifications() {
                $('.dropdown-menu').empty();

                messages.forEach(function (m) {
                    add_notification(m);
                });
            }

            function add_notification(m) {
                messages.push(m);

                $('.dropdown-menu').append('<a class="dropdown-item" href="#">' + m.message + '</a>');
                $('.notification').html(messages.length);
            }
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher('341e2a2f5770505ad5e9', {
                cluster: 'us2',
                encrypted: true
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function (data) {
                add_notification(data);
                console.info(JSON.stringify(data));
                return true;
            });

            channel.bind('pusher:subscription_succeeded', function (members) {
                console.info('successfully subscribed!');
            });

            $(document).ready(function() {
                $('.nav-link').click(function() {
                  if (messages.length == 0) {
                    return;
                  }
                    $('.dropdown-menu').toggle();

                });
                $('.user-link').click(function() {

                    $('.user-menu').toggle();

                });
            });

            function onThresholdProfileTypeChange() {
                if ($('#type').val() == "CPU") {
                    $('#warningleveltype').text('');
                    $('#errorleveltype').text('');
                }
                else {
                    $('#warningleveltype').text('%');
                    $('#errorleveltype').text('%');
                }
            }


        </script>
    </head>

    <body class="">

            <div class="sidebar" data-color="purple"
                data-background-color="white">
                <div class="logo">
                    <a href="http://www.creative-tim.com" class="simple-text
                        logo-normal">
                        Manage IT
                    </a>
                </div>
                <div class="sidebar-wrapper">


                    <ul class="nav">
                        <li class="{{ Route::currentRouteNamed('home') ? 'nav-item active' : 'nav-item' }}" >
                            <a class="nav-link" href="/">
                                <i class="material-icons">dashboard</i>
                                <p>Home: </p>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteNamed('servers') ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link" href="/servers">
                                <i class="material-icons">computer</i>
                                <p>Servers</p>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteNamed('servergroups') ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link" href="/servergroups">
                                <i class="material-icons">collections</i>
                                <p>Server Groups</p>
                            </a>
                        </li>
                        <li class="{{ Route::currentRouteNamed('thresholdprofiles') ? 'nav-item active' : 'nav-item' }}">
                            <a class="nav-link" href="/thresholdprofiles">
                                <i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>
                                <p>Threshold Profiles</p>
                            </a>
                        </li>


                    </ul>
                </div>
            </div>
            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg
                    navbar-absolute fixed-top">
                    <div class="container-fluid">

                        <button class="navbar-toggler" type="button"
                            data-toggle="collapse"
                            aria-controls="navigation-index"
                            aria-expanded="false"
                            aria-label="Toggle
                            navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="navbar-toggler-icon icon-bar"></span>
                            <span class="navbar-toggler-icon icon-bar"></span>
                            <span class="navbar-toggler-icon icon-bar"></span>
                        </button>
                        <div class="collapse navbar-collapse
                            justify-content-end">
                            <form class="navbar-form">
                                <span class="bmd-form-group">
                                    <div class="input-group no-border">
                                        <input type="text" value=""
                                            class="form-control"
                                            placeholder="Search...">
                                        <button type="submit" class="btn
                                            btn-white btn-round btn-just-icon">
                                            <i class="material-icons">search</i>
                                            <div class="ripple-container"></div>
                                        </button>
                                    </div>
                                </span>
                            </form>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#pablo">
                                        <i class="material-icons">dashboard</i>
                                        <p class="d-lg-none d-md-block">
                                            Stats
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link"
                                        href="#"
                                        id="navbarDropdownMenuLink"

                                        aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="material-icons">notifications</i>
                                        <span class="notification">0</span>
                                        <p class="d-lg-none d-md-block">
                                            Some Actions
                                        </p>
                                        <div class="ripple-container"></div>
                                    </a>
                                    <div style="display: none;position: absolute !important;left: auto !important;right: 0 !important;" class="dropdown-menu
                                        dropdown-menu-right"
                                        aria-labelledby="navbarDropdownMenuLink">
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link user-link" href="#">
                                        <i class="material-icons">person</i>

                                        <p class="d-lg-none d-md-block user-menu">
                                            Account
                                        </p>
                                    </a>
                                    <div style="display: none;position: absolute !important;left: auto !important;right: 0 !important;" class="user-menu
                                        dropdown-menu-right"
                                        aria-labelledby="navbarDropdownMenuLink">
                                        @guest
                                            <a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}</a>
                                            <a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        @else
                                                <a id="navbarDropdown" class="dropdown-item" href="#">
                                                    {{ Auth::user()->name }} <span class="caret"></span>
                                                </a>

                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                  onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                        @endguest
                                    </div>
                                  </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div id="app">
                  <!-- End Navbar -->
                  <div class="content">
                      <main class="py-4">
                          @yield('content')
                      </main>
                  </div>
          </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav class="float-left">
                        <ul>
                            <li>
                                <a href="https://www.creative-tim.com">
                                    Creative Tim
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright float-right">
                        &copy;
                        <script>
                        document.write(new Date().getFullYear())
                    </script>,
                        made with
                        <i class="material-icons">favorite</i> by
                        <a href="https://www.creative-tim.com" target="_blank">Creative
                            Tim</a> for a better web.
                    </div>
                    <!-- your footer here -->
                </div>
            </footer>
        </div>

        <!--   Core JS Files   -->
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
            integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
            crossorigin="anonymous"></script>

        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
            integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
            crossorigin="anonymous"></script>

        <script
            src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
            integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
            crossorigin="anonymous"></script>

        <script src="{{ asset('js/core/bootstrap-material-design.min.js') }}"></script>
        <script src="{{ asset('js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
        <script src="{{ asset('js/plugins/chartist.min.js') }}"></script>
        <script src="{{ asset('js/plugins/bootstrap-notify.js') }}"></script>
        <script src="{{ asset('js/material-dashboard.js') }}"></script>

    </body>

</html>

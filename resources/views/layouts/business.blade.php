<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('layout_style/img/wage_icon.png') }}">
    <title>
        @yield('title') | {{ env('APP_NAME') }}
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/css/feather.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('layout_style/css/style.css?v=') . time() }}">
    <link rel="stylesheet" href="{{ asset('layout_style/jquery_confirm/style.css') }}">
    <link rel="stylesheet" href="{{ asset('layout_style/css/my-style.css?v=') . time() }}">
    <link rel="stylesheet" href="{{ asset('layout_style/css/bootstrap-datetimepicker.min.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
    <script src="{{ asset('layout_style/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{ asset('layout_style/js/validations.js') }}"></script>
    <script src="{{ asset('layout_style/js/fileupload.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

    <!-- Include jQuery and jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Add this to your <head> or before the </body> tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

    <script type="text/javascript">
        window.history.forward();

        function noBack() {
            window.history.forward();
            window.menubar.visible = false;
        }
    </script>


    @yield('style')

</head>

<body onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                <a href="javascript:;" class="logo">
                    <img src="{{ asset('layout_style/img/wage_icon.png') }}" width="35" height="35" alt>
                    <span>{{ env('APP_NAME') }}</span>
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><img src="{{ asset('layout_style/img/icons/bars.ico') }}"
                    alt></a>
            <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img
                    src="{{ asset('layout_style/img/icons/bars.ico') }}" alt></a>

            {{-- start --}}

            @if (auth()->user()->hasRole('super_admin') ||
                    auth()->user()->hasRole('admin') ||
                    auth()->user()->hasRole('business_user'))
                <div class="top-nav-search mob-view">
                    <form>
                        <select class="form-control js-example-basic-single select2" id="change_business"
                            placeholder="Search here">
                            @foreach ($business as $item)
                                <option value="{{$item->id}}" {{session()->get('_business_id') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif
            {{-- end --}}
            <ul class="nav user-menu float-end">
                <li class="nav-item dropdown d-none d-md-block">
                    <a href="{{ route('business.reservation.create.form') }}" title="Create a new Reservation"
                        class="dropdown-toggle nav-link"><img
                            src="{{ asset('layout_style/img/icons/icons-plus.svg') }}" alt></a>
                </li>

                <li class="nav-item dropdown has-arrow user-profile-list">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
                        <div class="user-names">
                            <h5>{{ ucfirst(Auth::user()->first_name) . ' ' . ucfirst(Auth::user()->last_name) }} </h5>
                            {{-- <span>Admin</span> --}}
                        </div>
                        <span class="user-img">
                            <img src="{{ config('aws_url.url') . Auth::user()->UserProfile->profile }}" alt="Admin">
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('business.settings.profile') }}">My Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </li>

            </ul>
            <div class="dropdown mobile-user-menu float-end">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('settings.profile_update') }}">My Profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

        @php
            $segment = Request::segment(1);
            $segment2 = Request::segment(2);
        @endphp

        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">{{ session()->get('_company_name') }}</li>

                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->route()->getName() == 'admin.business' ? 'active' : '' }}">
                                <span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/menu-icon-01.ico') }}" alt>
                                </span>
                                <span>Dashboard</span>
                            </a>
                        </li>


                        @if (Auth::user()->hasPermissionTo('Read_Client'))
                            @php
                                $client_route_name = [
                                    'business.clients',
                                    'business.clients.create.form',
                                    'business.clients.update.form',
                                ];
                            @endphp

                            <li>
                                <a href="{{ route('business.clients') }}"
                                    class="{{ in_array(request()->route()->getName(), $client_route_name) ? 'active' : '' }}">
                                    <span class="menu-side">
                                        <img src="{{ asset('layout_style/img/icons/user-plus.ico') }}" alt>
                                    </span>
                                    <span>Clients</span>
                                </a>
                            </li>
                        @endif

                        {{-- table cafe  part --}}
                        @if (Auth::user()->hasPermissionTo('Read_CafeTable'))
                            <li>
                                @php
                                    $cafe_route_name = ['business.cafe'];
                                @endphp
                                <a href="{{ route('business.cafe') }}"
                                    class="{{ in_array(request()->route()->getName(), $cafe_route_name) ? 'active' : '' }}">
                                    <span class="menu-side">
                                        <img src="{{ asset('layout_style/img/icons/table.ico') }}"
                                            style="width: 24px" alt>
                                    </span>
                                    <span>Tables</span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->hasPermissionTo('Read_Users'))
                            <li>
                                @php
                                    $users_route_name = [
                                        'business.users',
                                        'business.users.create.form',
                                        'business.users.update.form',
                                    ];
                                @endphp
                                <a href="{{ route('business.users') }}"
                                    class="{{ in_array(request()->route()->getName(), $users_route_name) ? 'active' : '' }}">
                                    <span class="menu-side">
                                        <img src="{{ asset('layout_style/img/icons/users.ico') }}" alt>
                                    </span>
                                    <span>Users</span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->hasPermissionTo('Read_Reservation'))
                            <li class="submenu">
                                @php
                                    $reservation_route_name = [
                                        'business.reservation',
                                        'business.reservation.create.form',
                                        'business.reservation.update.form',
                                    ];

                                    $calendar_route_name = [
                                        'business.calendar',
                                        'business.calendar.create.form',
                                        'business.calendar.update.form',
                                    ];

                                    $floorplan_route_name = [
                                        'business.floor',
                                    ];
                                @endphp
                                <a href="javascript:;"><span class="menu-side"><img
                                            src="{{ asset('layout_style/img/icons/table-add.ico') }}"
                                            style="width: 24px" alt></span>
                                    <span> Reservation </span> <span class="menu-arrow"></span></a>

                                <ul style="display: none;">
                                    <li><a class="{{ in_array(request()->route()->getName(), $calendar_route_name) ? 'active' : '' }}"
                                            href="{{ route('business.calendar') }}"
                                            id="attendances_menu_link">Calendar</a></li>

                                    <li><a class="{{ in_array(request()->route()->getName(), $reservation_route_name) ? 'active' : '' }}"
                                            href="{{ route('business.reservation') }}"
                                            id="attendances_list_menu_link">List</a></li>

                                    <li><a class="{{ in_array(request()->route()->getName(), $floorplan_route_name) ? 'active' : '' }}"
                                        href="{{ route('business.floor') }}"
                                        id="attendances_list_menu_link">Floor Plan</a></li>
                                </ul>
                            </li>
                        @endif

                        @if (Auth::user()->hasPermissionTo('Read_Report'))
                            <li>
                                @php
                                    $reports_route_name = ['cafe.reports'];
                                @endphp
                                <a href="{{ route('cafe.reports') }}"
                                    class="{{ in_array(request()->route()->getName(), $reports_route_name) ? 'active' : '' }}">
                                    <span class="menu-side">
                                        <img src="{{ asset('layout_style/img/icons/report.ico') }}"
                                            style="width: 24px" alt>
                                    </span>
                                    <span>Reports</span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->hasPermissionTo('Read_Report'))
                            <li>
                                @php
                                    $reports_route_name = ['business.IntakeForm.index'];
                                @endphp
                                <a href="{{ route('business.IntakeForm.index') }}"
                                    class="{{ in_array(request()->route()->getName(), $reports_route_name) ? 'active' : '' }}">
                                    <span class="menu-side">
                                        <img src="{{ asset('layout_style/img/icons/report.ico') }}"
                                            style="width: 24px" alt>
                                    </span>
                                    <span>Intake Form</span>
                                </a>
                            </li>
                        @endif

                        <li class="submenu">
                            @php

                                $location_route_name = [
                                    'business.Locations',
                                    'business.Locations.create.form',
                                    'business.Locations.update.form',
                                ];
                                $preference_route_name = [
                                    'business.preference',
                                    'business.preference.create.form',
                                    'business.preference.update.form',
                                ];

                                $floor_plan_route_name = [
                                    'business.floor_plan',
                                    'business.floor_plan.create.form',
                                    'business.floor_plan.update.form',
                                ];
                                $notification_route_name = ['business.settings.notifications'];
                                $profile_route_name = ['business.settings.profile'];

                            @endphp

                            <a href="javascript:;"><span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/setting-icon-01.ico') }}"
                                        style="width: 24px" alt></span>
                                <span> Settings </span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">

                                {{-- Locations --}}
                                @if (Auth::user()->hasPermissionTo('Read_Location'))
                                    <li>
                                        <a href="{{ route('business.Locations') }}"
                                            class="{{ in_array(request()->route()->getName(), $location_route_name) ? 'active' : '' }}">
                                            <span>Locations</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- table Section --}}
                                @if (Auth::user()->hasPermissionTo('Read_Preference'))
                                    <li>
                                        <a href="{{ route('business.preference') }}"
                                            class="{{ in_array(request()->route()->getName(), $preference_route_name) ? 'active' : '' }}">
                                            <span>Sections</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- Floor Plan --}}
                                @if (Auth::user()->hasPermissionTo('Read_Floor_Plan'))
                                    <li>
                                        <a href="{{ route('business.floor_plan') }}"
                                            class="{{ in_array(request()->route()->getName(), $floor_plan_route_name) ? 'active' : '' }}">
                                            <span>Floor Plans</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- Notifications --}}
                                @if (Auth::user()->hasPermissionTo('Read_Notification'))
                                    <li><a class="{{ in_array(request()->route()->getName(), $notification_route_name) ? 'active' : '' }}"
                                            href="{{ route('business.settings.notifications') }}"
                                            id="attendances_menu_link">Notifications</a>
                                    </li>
                                @endif

                                {{-- Profile --}}
                                <li><a class="{{ in_array(request()->route()->getName(), $profile_route_name) ? 'active' : '' }}"
                                        href="{{ route('business.settings.profile') }}"
                                        id="attendances_list_menu_link">Profile</a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <div class="logout-btn">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span
                                class="menu-side"><img src="{{ asset('layout_style/img/icons/logout.png') }}"
                                    alt width="24px"></span>
                            <span style="color: rgba(51,53,72,.75);">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-wrapper">
            <div class="content">

                @yield('content')

            </div>
        </div>

        <!--loader-->
        <div class="ajax-loader" id="loader" style="display: none">
            <div class="max-loader">
                <div class="loader-inner">
                    <div class="spinner-border text-white" role="status"></div>
                    <p>Please Wait........</p>
                </div>
            </div>
        </div>
        <!--end loader-->
    </div>
    <div class="sidebar-overlay" data-reff></div>



    <script src="{{ asset('layout_style/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('layout_style/js/app.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/select2/js/custom-select.js') }}"></script>
    <script src="{{ asset('layout_style/jquery_confirm/script.js') }}"></script>
    <script src="{{ asset('layout_style/jquery_confirm/popup.js') }}"></script>

    <script src="{{ asset('layout_style/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.counterup.min.js') }}"></script>

    <script src="{{ asset('layout_style/cdn_scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/apexchart/chart-data.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        $(document).ready(function() {
            $('.select2').select2()

            $('#change_business').on('change', function() {
                var businessId = $(this).val();

                var data = {
                    id: businessId,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.change_business') }}",
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == false) {
                            successMessage('Failed!', response.message, "");
                        } else {
                            location.href = "{{route('dashboard')}}"
                        }
                    },
                    statusCode: {
                        401: function() {
                            window.location.href = '{{ route('login') }}';
                        },
                        419: function() {
                            window.location.href = '{{ route('login') }}';
                        }
                    }
                });
            });
        });
    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>


    @yield('scripts')
</body>

</html>

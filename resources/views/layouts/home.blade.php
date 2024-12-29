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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/5.39.0/css/tempus-dominus.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
    <script src="{{ asset('layout_style/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/5.39.0/js/tempus-dominus.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script type="text/javascript">
        window.history.forward();

        function noBack() {
            window.history.forward();
            window.menubar.visible = false;
        }
    </script>


</head>

<body onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
    <div class="main-wrapper">
        <div class="header bg-body-secondary business-side">
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

            <ul class="nav user-menu float-end">

                <li class="nav-item dropdown has-arrow user-profile-list">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
                        <div class="user-names">
                            <h5>{{ ucfirst(Auth::user()->first_name) . ' ' . ucfirst(Auth::user()->last_name) }} </h5>
                            {{-- <span>Admin</span> --}}
                        </div>
                        <span class="user-img">
                            {{-- <img src="{{ config('aws_url.url') . Auth::user()->UserProfile->profile }}"
                                width="40px" height="40px" alt="Admin"> --}}
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{route('admin.profile.index')}}">My Profile</a>
                        <a class="dropdown-item" href="{{ route('admin.profile.index') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </li>

            </ul>
            <div class="dropdown mobile-user-menu float-end">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="">My Profile</a>
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

        <div class="sidebar bg-body-secondary" id="sidebar">
            <div class="sidebar-inner slimscroll business_sidebar">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">{{ session()->get('_company_name') }}</li>

                        <li>
                            <a href="{{route('admin.business')}}"
                                class="{{ request()->route()->getName() == 'admin.business' ? 'active' : '' }}"
                                >
                                <span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/menu-icon-01.ico') }}" alt>
                                </span>
                                <span>Business</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{route('admin.business-users')}}"
                                class="{{ request()->route()->getName() == 'admin.business-users' ? 'active' : '' }}"
                                >
                                <span class="menu-side">
                                    <img src="{{ asset('layout_style/img/icons/users.ico') }}" alt>
                                </span>
                                <span>Business Users</span>
                            </a>
                        </li>

                        @auth
                            @if(Auth::user()->hasRole('super_admin'))
                                <li>
                                    <a href="{{ route('admin.admin-users') }}" class="{{ request()->route()->getName() == 'admin.admin-users' ? 'active' : '' }}">
                                        <span class="menu-side">
                                            <img src="{{ asset('layout_style/img/icons/admins.ico') }}" alt>
                                        </span>
                                        <span>Admin Users</span>
                                    </a>
                                </li>
                            @endif
                        @endauth

                        <li class="submenu">
                            @php
                                $table_types = [
                                    'admin.table_types',
                                    'admin.table_types',
                                    'admin.table_types',
                                ];

                                $table_layout = [
                                    'admin.elements',
                                    'admin.elements.create.form',
                                    'admin.elements.update.form',
                                ];
                            @endphp
                            <a href="javascript:;"><span class="menu-side"><img
                                        src="{{ asset('layout_style/img/icons/setting-icon-01.ico  ') }}"
                                        style="width: 24px" alt></span>
                                <span> Settings </span> <span class="menu-arrow"></span></a>

                            <ul style="display: none;">
                                <li><a class="{{ in_array(request()->route()->getName(), $table_types) ? 'active' : '' }}"
                                        href="{{ route('admin.table_types') }}"
                                        id="attendances_menu_link">Element Types</a></li>

                                <li><a class="{{ in_array(request()->route()->getName(), $table_layout) ? 'active' : '' }}"
                                        href="{{ route('admin.elements') }}"
                                        id="attendances_list_menu_link">Elements</a></li>

                            </ul>
                        </li>

                    </ul>
                    <div class="logout-btn">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span
                                class="menu-side"><img src="{{ asset('layout_style/img/icons/logout.png') }}" width="24px"
                                    alt></span>
                            <span>Logout</span></a>
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
    <script src="{{ asset('layout_style/js/validations.js') }}"></script>
    <script src="{{ asset('layout_style/jquery_confirm/script.js') }}"></script>
    <script src="{{ asset('layout_style/jquery_confirm/popup.js') }}"></script>

    <script src="{{ asset('layout_style/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('layout_style/js/jquery.counterup.min.js') }}"></script>

    <script src="{{ asset('layout_style/cdn_scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('layout_style/plugins/apexchart/chart-data.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2()

            function fetchBusinessList() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard.get_business_list') }}",
                    dataType: "JSON",
                    success: function(response) {

                        var $select = $('#change_business');
                        $select.empty();
                        $select.append('<option value="">-- Select the Business --</option>');

                        $.each(response.all_business_list, function(index, business) {
                            $select.append('<option value="' + business.id + '"' +
                                (business.id == '{{ session()->get("_business_id") }}' ? ' selected' : '') +
                                '>' + business.name + '</option>');
                        });
                    },
                    statusCode: {
                        401: function() {
                            // Redirect to login if unauthorized
                            window.location.href = '{{ route('login') }}';
                        },
                        419: function() {
                            // Handle session timeout
                            window.location.href = '{{ route('login') }}';

                        }
                    }
                });
            }

            fetchBusinessList();

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
                            fetchBusinessList();
                            window.location.reload();

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




    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>

    <script src="{{asset('layout_style/js/fileupload.js')}}"></script>
    @yield('scripts')
</body>

</html>

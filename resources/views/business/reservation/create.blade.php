@extends('layouts.business')

@section('title')
    Manage Reservations
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.reservation') }}">Manage Reservations </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Create new Reservation</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.reservation') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form id="submitForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-heading">
                                    <h4>Create New Reservation</h4>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="col-md-12 col-xl-12 col-12">
                                    <div class="input-block local-forms reservation_list_plus">
                                        <label>Select Client <span class="login-danger">*</span></label>
                                        <select name="client" class="form-control client select2" id="client">
                                            <option value="">--select client--</option>
                                            @foreach ($client as $item)
                                                <option value="{{ $item->id }}">{{ $item->name . ' - ' . $item->contact }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if (Auth::user()->hasPermissionTo('Create_Client'))
                                            <a href="javascript:;" onclick="create_client_model()" title="Create a new Client" class="">
                                                <img src="{{ asset('layout_style/img/icons/icons-plus.svg') }}" alt>
                                            </a>
                                        @endif
                                        <small class="text-danger font-weight-bold err_client"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Select Location <span class="login-danger">*</span></label>
                                    <select name="location" class="form-control location select2" id="location">
                                        <option value="">--select location--</option>
                                        @foreach ($location as $item)
                                            <option value="{{ $item->id }}">{{ $item->location_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_location"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="input-block local-forms ">
                                    <label>Requested Date <span class="login-danger">*</span></label>
                                    <input class="form-control" name="requested_date" id="requested_date" type="date"
                                        min="{{ date('Y-m-d') }}">
                                    <small class="text-danger font-weight-bold err_requested_date"></small>
                                </div>

                            </div>

                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="input-block local-forms">
                                    <label>Start Time <span class="login-danger">*</span></label>
                                    <div class="time-icon">
                                        <input type="text" class="form-control time_picker_field start_time"
                                            name="start_time" id="start_time">
                                    </div>
                                    <small class="text-danger font-weight-bold err_start_time"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="input-block local-forms">
                                    <label>End Time <span class="login-danger">*</span></label>
                                    <div class="time-icon">
                                        <input type="text" class="form-control time_picker_field end_time"
                                            name="end_time" id="end_time">
                                    </div>
                                    <small class="text-danger font-weight-bold err_end_time"></small>
                                </div>
                            </div>

                            <div class="col-sm-12 _div_section_div">

                            </div>

                            <div class="col-12 col-md-12 col-xl-12 text-center mt-4">
                                <h4 class="text-danger err_no_table"></h4>
                            </div>

                            {{-- @if (Auth::user()->hasPermissionTo('Create_Reservation'))
                                <div class="col-12 mb-4">
                                    <div class="doctor-submit text-end">
                                        <button type="button" onclick="search_table()"
                                            class="btn btn-primary text-uppercase submit-form me-2">Search Table</button>
                                    </div>
                                </div>
                            @endif --}}

                            <div class="available_table_data col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                            </div>

                            @if (Auth::user()->hasPermissionTo('Create_Reservation'))
                                <div class="col-12 save_button_div" style="display: none">
                                    <div class="doctor-submit text-end">
                                        <button type="submit"
                                            class="btn btn-primary text-uppercase submit-form me-2">Save</button>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('business.reservation.create_client')
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(function() {
                $('.time_picker_field').datetimepicker({
                    format: 'LT',
                    stepping: 30,
                    icons: {
                        up: "fas fa-angle-up",
                        down: "fas fa-angle-down",
                        next: 'fas fa-angle-right',
                        previous: 'fas fa-angle-left'
                    }
                });
            });

        });

        function search_table() {
            var data = {
                'client': $('#client').val(),
                'location': $('#location').val(),
                'requested_date': $('#requested_date').val(),
                'start_time': $('#start_time').val(),
                'end_time': $('#end_time').val()
            }

            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $('#loader').show()
                },
                url: "{{ route('business.reservation.get_available_table') }}",
                data: data,
                success: function(response) {
                    $('#loader').hide()

                    clearError();

                    if (response.status == false) {
                        $.each(response.message, function(key, item) {
                            if (key) {
                                $('.err_' + key).text(item)
                            }
                        });
                        $('.save_button_div').hide()

                    } else {
                        $('.available_table_data').html('')
                        $('.available_table_data').html(response)
                        $('.save_button_div').show()
                    }
                },
                statusCode: {
                    401: function() {
                        window.location.href =
                            '{{ route('login') }}'; //or what ever is your login URI
                    },
                    419: function() {
                        window.location.href =
                            '{{ route('login') }}'; //or what ever is your login URI
                    },
                },
                error: function(data) {
                    someThingWrong();
                }
            });
        }

        $('#location').change(function (e) {
            e.preventDefault();

            var data = {
                'location': $(this).val(),
            }

            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $('#loader').show()
                },
                url: "{{ route('business.reservation.get_section') }}",
                data: data,
                success: function(response) {
                    $('#loader').hide()

                    clearError();
                    $('.available_table_data').html('')
                    $('._div_section_div').html('')

                    if (response.status == false) {
                        errorPopup(response.message, '')
                    } else {
                        $('._div_section_div').html(response)
                    }

                },
                statusCode: {
                    401: function() {
                        window.location.href =
                            '{{ route('login') }}'; //or what ever is your login URI
                    },
                    419: function() {
                        window.location.href =
                            '{{ route('login') }}'; //or what ever is your login URI
                    },
                },
                error: function(data) {
                    someThingWrong();
                }
            });

        });

        $('#submitForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData($('#submitForm')[0]);

            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $('#loader').show()
                },
                url: "{{ route('business.reservation.create') }}",
                data: formData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('#loader').hide()

                    clearError();

                    if (response.status == false) {
                        $.each(response.message, function(key, item) {
                            if (key) {
                                $('.err_' + key).text(item)
                            }
                        });
                    } else {
                        successPopup(response.message, response.route)
                    }
                },
                statusCode: {
                    401: function() {
                        window.location.href =
                            '{{ route('login') }}'; //or what ever is your login URI
                    },
                    419: function() {
                        window.location.href =
                            '{{ route('login') }}'; //or what ever is your login URI
                    },
                },
                error: function(data) {
                    someThingWrong();
                }
            });
        });

        function clearError() {
            $('.err_client').text('');

            $('.err_requested_date').text('');

            $('.err_start_time').text('');

            $('.err_end_time').text('');

            $('.err_table').text('');

            $('.err_no_of_people').text('');

            $('.err_table_amount').text('');

            $('.err_discount_amount').text('');

            $('.err_service_charge').text('');

            $('.err_total_amount').text('');

            $('.err_location').text('');

            $('.err_no_table').text('')
        }

        $('#location').change(function(e) {
            e.preventDefault();
            var requested_date = $('#requested_date').val()

            var data = {
                'requested_date': requested_date,
                'location': $(this).val()
            }

            if (requested_date != '') {

                $('#loader').show()
                $.ajax({
                    type: "POST",
                    url: "{{ route('business.reservation.get_existing_booking') }}",
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        $('#loader').hide()
                        clearError()
                        if (response.status == false) {
                            $.each(response.message, function(key, item) {
                                if (key) {
                                    $('.err_' + key).text(item)
                                    $('#' + key).addClass('is-invalid');
                                }
                            });
                        } else {
                            $('.save_button_div').hide()
                            $('.available_table_data').html('')
                            $('.available_table_data').html(response);
                        }
                    },
                    statusCode: {
                        401: function() {
                            window.location.href =
                                '{{ route('login') }}'; //or what ever is your login URI
                        },
                        419: function() {
                            window.location.href =
                                '{{ route('login') }}'; //or what ever is your login URI
                        },
                    },
                    error: function(data) {
                        someThingWrong();
                    }
                });
            }
        });

        $('#requested_date').change(function(e) {
            e.preventDefault();
            $('#loader').show()

            var location = $('#location').val()

            var data = {
                'requested_date': $(this).val(),
                'location': $('#location').val()
            }

            if (location == '') {
                $('.err_location').text('The location field is required.');
                $('#requested_date').val('')
                $('#loader').hide()
            } else {

                $.ajax({
                    type: "POST",
                    url: "{{ route('business.reservation.get_existing_booking') }}",
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        $('#loader').hide()
                        clearError()
                        if (response.status == false) {
                            $.each(response.message, function(key, item) {
                                if (key) {
                                    $('.err_' + key).text(item)
                                    $('#' + key).addClass('is-invalid');
                                }
                            });
                        } else {
                            $('.save_button_div').hide()
                            $('.available_table_data').html('')
                            $('.available_table_data').html(response);
                        }
                    },
                    statusCode: {
                        401: function() {
                            window.location.href =
                                '{{ route('login') }}'; //or what ever is your login URI
                        },
                        419: function() {
                            window.location.href =
                                '{{ route('login') }}'; //or what ever is your login URI
                        },
                    },
                    error: function(data) {
                        someThingWrong();
                    }
                });
            }
        });

        function create_client_model()
        {
            var myOffcanvas = document.getElementById('offcanvasRight')
                    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
                    bsOffcanvas.show()
        }
    </script>
@endsection

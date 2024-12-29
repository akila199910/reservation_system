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
                    <li class="breadcrumb-item active">Update Reservation</li>
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
                                    <h4>Update Reservation</h4>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $reservation->id }}">
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Select Client <span class="login-danger">*</span></label>
                                    <select name="client" class="form-control client select2" id="client">
                                        <option value="">--select client--</option>
                                        @foreach ($client as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $reservation->client_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name . ' - ' . $item->contact }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_client"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Select Location <span class="login-danger">*</span></label>
                                    <select name="location" class="form-control location select2" id="location">
                                        <option value="">--select location--</option>
                                        @foreach ($location as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $reservation->location_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->location_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_location"></small>
                                </div>
                            </div>

                            @php
                                $requested_date = $reservation->request_date;
                                $current_date = date('Y-m-d');

                                $min_date = $current_date;
                                if(strtotime($current_date) > strtotime($requested_date))
                                {
                                    $min_date = $requested_date;
                                }
                            @endphp

                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="input-block local-forms ">
                                    <label>Requested Date <span class="login-danger">*</span></label>
                                    <input class="form-control" name="requested_date" id="requested_date" type="date"
                                        min="{{ $min_date }}" value="{{ $reservation->request_date }}">
                                    <small class="text-danger font-weight-bold err_requested_date"></small>
                                </div>

                            </div>

                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="input-block local-forms">
                                    <label>Start Time <span class="login-danger">*</span></label>
                                    <div class="time-icon">
                                        <input type="text" class="form-control time_picker_field start_time"
                                            value="{{ date('h:i A', strtotime($reservation->request_start_time)) }}"
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
                                            value="{{ date('h:i A', strtotime($reservation->request_end_time)) }}"
                                            name="end_time" id="end_time">
                                    </div>
                                    <small class="text-danger font-weight-bold err_end_time"></small>
                                </div>
                            </div>

                            <div class="col-sm-12 _div_section_div">
                                @include('business.reservation.update_section_content')
                            </div>

                            <div class="col-12 col-md-12 col-xl-12 text-center mt-4">
                                <h4 class="text-danger err_no_table"></h4>
                            </div>

                            <div class="available_table_data col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                @include('business.reservation.update_content')
                            </div>

                            @if ($reservation->paid_status == 0)
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label>Select Payment Type</label>
                                        <select name="payment_type" id="payment_type" class="form-control payment_type">
                                            <option value="0">--Select a payment method--</option>
                                            <option value="1" {{ $reservation->payment_type == 1 ? 'selected' : '' }}>
                                                Direct Paid</option>
                                            <option value="2" {{ $reservation->payment_type == 2 ? 'selected' : '' }}>
                                                Online Paid</option>
                                        </select>

                                        <small class="text-danger font-weight-bold err_payment_type"></small>
                                    </div>
                                </div>
                            @endif

                            @if (Auth::user()->hasPermissionTo('Update_Reservation'))
                                <div class="col-12 update_button_div">
                                    <div class="doctor-submit text-end">
                                        <button type="submit"
                                            class="btn btn-primary text-uppercase submit-form me-2">Update</button>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.update_button_div').hide()
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

            check_record_data()
        });

        function check_record_data()
        {
            var data = {
                'reservation_id' : '{{$reservation->id}}'
            }

            $.ajax({
                type: "POST",
                url: "{{route('business.reservation.get_update_data')}}",
                data: data,
                dataType: "JSON",
                success: function (response) {
                    if (response.status == false) {
                        $('.update_button_div').hide()
                    } else {
                        $('.update_button_div').show()
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

        function search_table() {
            var data = {
                'client': $('#client').val(),
                'location': $('#location').val(),
                'requested_date': $('#requested_date').val(),
                'start_time': $('#start_time').val(),
                'end_time': $('#end_time').val(),
                'table_id': '{{ $reservation->table_info->ref_no }}'
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
                    } else {
                        $('.available_table_data').html('')
                        $('.available_table_data').html(response)
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
                'reservation_id': '{{ $reservation->id }}',
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
                    $('input[name="preference"]:checked').prop('checked', false);
                    $('.update_button_div').css('display','none')
                    if (response.status == false) {
                        errorPopup(response.message, '')
                    } else {
                        $('._div_section_div').html(response)
                        // $('.update_button_div').css('display','block')
                        $('input[name="preference"]:checked').prop('checked', false);
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
                url: "{{ route('business.reservation.update') }}",
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
        }

        // $('#location').change(function(e) {
        //     e.preventDefault();
        //     var requested_date = $('#requested_date').val()

        //     var data = {
        //         'requested_date': requested_date,
        //         'location': $(this).val()
        //     }

        //     if (requested_date != '') {

        //         $('#loader').show()
        //         $.ajax({
        //             type: "POST",
        //             url: "{{ route('business.reservation.get_existing_booking') }}",
        //             data: data,
        //             dataType: "JSON",
        //             success: function(response) {
        //                 $('#loader').hide()
        //                 clearError()
        //                 if (response.status == false) {
        //                     $.each(response.message, function(key, item) {
        //                         if (key) {
        //                             $('.err_' + key).text(item)
        //                             $('#' + key).addClass('is-invalid');
        //                         }
        //                     });
        //                 } else {
        //                     $('.available_table_data').html('')
        //                     $('.available_table_data').html(response);
        //                 }
        //             },
        //             statusCode: {
        //                 401: function() {
        //                     window.location.href =
        //                         '{{ route('login') }}'; //or what ever is your login URI
        //                 },
        //                 419: function() {
        //                     window.location.href =
        //                         '{{ route('login') }}'; //or what ever is your login URI
        //                 },
        //             },
        //             error: function(data) {
        //                 someThingWrong();
        //             }
        //         });
        //     }
        // });

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
    </script>
@endsection

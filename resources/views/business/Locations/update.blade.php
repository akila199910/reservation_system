@extends('layouts.business')

@section('title')
Manage Business Locations
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.Locations') }}">Manage Business Locations </a>
                    </li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Update Business Location</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.Locations') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <form id="submitForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-heading">
                                    <h4>Update Business Location</h4>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $find__location->id }}">



                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Location Name <span class="login-danger">*</span></label>
                                    <input type="text" name="location_name" class="form-control location_name " id="location_name"
                                        maxlength="190" value="{{ $find__location->location_name }}">
                                    <small class="text-danger font-weight-bold err_location_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Location Address <span class="login-danger">*</span></label>
                                    <input type="text" name="address" class="form-control address" id="address"
                                        maxlength="190" value="{{ $find__location->address }}">
                                    <small class="text-danger font-weight-bold err_address"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input type="text" name="email" class="form-control email" id="email"
                                        maxlength="190" value="{{ $find__location->email }}">
                                    <small class="text-danger font-weight-bold err_email"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Contact <span class="login-danger">*</span></label>
                                    <input type="text" name="contact_no" class="form-control contact number_only_val"
                                        id="contact_no" maxlength="10" value="{{ $find__location->contact_no }}">
                                    <small class="text-danger font-weight-bold err_contact_no"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Google Location Link</label>
                                    <input type="text" name="google_location" class="form-control google_location"
                                        id="google_location" maxlength="190" value="{{ $find__location->google_location }}">
                                    <small class="text-danger font-weight-bold err_google_location"></small>
                                </div>
                            </div>

                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longtitude">
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block select-gender">
                                    <label class="gen-label">Status Inactive/Active</label>
                                    <div class="status-toggle d-flex justify-content-between align-items-center">
                                        <input type="checkbox" id="status" name="status"
                                            {{ $find__location->status == 1 ? 'checked' : '' }} class="check">
                                        <label for="status" class="checktoggle">checkbox</label>
                                    </div>
                                </div>
                            </div>

                            {{-- @if (Auth::user()->hasPermissionTo('Update_Location')) --}}
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block select-gender">
                                        <label class="gen-label">Is Default <small class="text-primary">(If enable this
                                                location, existing default change to no)</small></label>
                                        <div class="status-toggle d-flex justify-content-between align-items-center">
                                            <input type="checkbox" id="is_default" name="is_default"
                                                {{ $find__location->is_default == 1 ? 'checked' : '' }} class="check">
                                            <label for="is_default" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                                </div>
                            {{-- @endif --}}
                        </div>
                    </div>
                </div>
                {{-- This is working hours part --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-heading">
                                    <h4>Business Location Working Hours</h4>
                                </div>
                            </div>
                            @foreach ($weeks_days as $day)

                                @php
                                    $workingHour = $find__location->workingHours->firstWhere('week_day', $day);
                                @endphp

                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="card mb-3 border">
                                        <div class="card-body">
                                            <div class="input-block local-forms">
                                                <label>Start Time {{ $day }} </label>
                                                <div class="time-icon">
                                                    <input type="text" class="form-control time_picker_field start_time" name="open_time_{{ $day }}"
                                                        id="openTime_{{ $day }}"
                                                        value="{{ $workingHour ? \Carbon\Carbon::parse($workingHour->opens_at)->format('g:i A') : '' }}">
                                                </div>
                                                <small class="text-danger font-weight-bold err_open_time_{{ $day }}"></small>
                                            </div>

                                            <div class="input-block local-forms">
                                                <label>End Time {{ $day }} </label>
                                                <div class="time-icon">
                                                    <input type="text" class="form-control time_picker_field end_time" name="close_time_{{ $day }}"
                                                        id="closeTime_{{ $day }}"
                                                        value="{{ $workingHour ? \Carbon\Carbon::parse($workingHour->close_at)->format('g:i A') : '' }}">
                                                </div>
                                                <small class="text-danger font-weight-bold err_close_time_{{ $day }}"></small>
                                            </div>

                                            <div class="text-center">
                                                <label>{{ $day }} Status</label>
                                                <div class="d-flex justify-content-center align-items-center flex-column">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="status_{{ $day }}" value="0">
                                                        <input type="checkbox" id="status_{{ $day }}" name="status_{{ $day }}" value="1" class="form-check-input mb-4"
                                                        {{ $workingHour && $workingHour->status == 1 ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <small class="text-danger font-weight-bold err_status_{{ $day }}"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            @if (Auth::user()->hasPermissionTo('Update_Location'))
                                <div class="row">
                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit"
                                                class="btn btn-primary text-uppercase submit-form me-2">Update</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALI1s4naJlQ35vpGOAcQFV-X1jze6m408&libraries=places"></script>


<script>
    function initialize() {
        var autocomplete;
        var input = document.getElementById('address');
        autocomplete = new google.maps.places.Autocomplete(input, { types: ['geocode'] });


        autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();

            if (place.geometry) {
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
            } else {
                alert("No details available for the input: '" + place.name + "'");
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize());
</script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        })

        $(function() {
                $('.time_picker_field').datetimepicker({
                    format: 'LT',
                    stepping: 15 ,
                    icons: {
                        up: "fas fa-angle-up",
                        down: "fas fa-angle-down",
                        next: 'fas fa-angle-right',
                        previous: 'fas fa-angle-left'
                    }
                });
            });

        $('#submitForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData($('#submitForm')[0]);

            let isValid = validateTimesBeforeSubmit();
                if (!isValid) {
                    $('#loader').hide();
                    return false;
                }
            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $("#loader").show();
                },
                url: "{{ route('business.Locations.update') }}",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $("#loader").hide();
                    clearError();
                    if (response.status == false) {
                        $.each(response.message, function(key, item) {
                            if (key) {
                                $('.err_' + key).text(item)
                                $('#' + key).addClass('is-invalid');
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

        function validateTimesBeforeSubmit() {
                let isValid = true;
                const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                let atLeastOneChecked = false;


                days.forEach(function(day) {
                    let status = $('#status_' + day).is(':checked');
                    let openTime = $('#openTime_' + day).val();
                    let closeTime = $('#closeTime_' + day).val();

                    if (status) {

                        atLeastOneChecked = true;
                        }

                    if (status && (!openTime || !closeTime)) {
                        isValid = false;

                        if (!openTime) {
                            $('#openTime_' + day).addClass('is-invalid');
                            $('.err_open_time_' + day).text('Open time is required.');
                        }

                        if (!closeTime) {
                                            $('#closeTime_' + day).addClass('is-invalid');
                                            $('.err_close_time_' + day).text('Close time is required.');
                                        }
                                    }
                                });

                                if (!atLeastOneChecked) {
                                    isValid = false;
                                    days.forEach(function(day) {
                                        $('.err_status_' + day).text('At least one day must have status checked.');
                                    });
                                }

                                return isValid;
                            }
        function clearError() {
            $('#bname').removeClass('is-invalid');
            $('.err_bname').text('');

            $('#location_name').removeClass('is-invalid');
            $('.err_location_name').text('');

            $('#email').removeClass('is-invalid');
            $('.err_email').text('');

            $('#contact_no').removeClass('is-invalid');
            $('.err_contact_no').text('');

            $('#google_location').removeClass('is-invalid');
            $('.err_google_location').text('');

            $('#address').removeClass('is-invalid');
            $('.err_address').text('');
        }
    </script>
@endsection

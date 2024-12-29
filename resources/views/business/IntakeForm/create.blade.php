@extends('layouts.business')

@section('title')
Manage Intake Form
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.IntakeForm.index') }}">Manage Intake Form </a>
                    </li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Create new Intake Form</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.IntakeForm.index') }}" class="btn btn-primary" style="width: 100px">Back</a>
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
                                    <h4>Create New Intake Form</h4>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>First Name <span class="login-danger">*</span></label>
                                    <input type="text" name="f_name" class="form-control f_name" id="f_name"
                                        maxlength="190">
                                    <small class="text-danger font-weight-bold err_f_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Last Name <span class="login-danger">*</span></label>
                                    <input type="text" name="l_name" class="form-control l_name" id="l_name"
                                        maxlength="190">
                                    <small class="text-danger font-weight-bold err_l_name"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="address">Address<span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control address" id="address" maxlength="190">
                                    <small class="text-danger font-weight-bold err_address"></small>
                                </div>
                            </div>

                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longtitude">

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input type="text" name="email" class="form-control email" id="email"
                                        maxlength="190">
                                    <small class="text-danger font-weight-bold err_email"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Contact <span class="login-danger">*</span></label>
                                    <input type="text" name="contact" class="form-control contact number_only_val"
                                        id="contact" maxlength="10">
                                    <small class="text-danger font-weight-bold err_contact"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Date of Birth <span class="login-danger">*</span></label>
                                    <input class="form-control" name="dob" id="dob" type="date"
                                        max="{{ date('Y-m-d') }}">
                                    <small class="text-danger font-weight-bold err_dob"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label> Gender <span class="login-danger">*</span></label>
                                    <select name="gender" class="form-control gender" id="gender">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                        <option value="O">Other</option>
                                    </select>
                                    <small class="text-danger font-weight-bold err_gender"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms ">
                                    <label>Appointment Date <span class="login-danger">*</span></label>
                                    <input class="form-control" name="appointment_date" id="appointment_date" type="date"
                                        min="{{ date('Y-m-d') }}">
                                    <small class="text-danger font-weight-bold err_appointment_date"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Communication Mode <span class="login-danger">*</span></label>
                                    <select name="communication_mode" class="form-control communication_mode" id="communication_mode" required>
                                        <option value="" disabled selected>Select communication mode</option>
                                        <option value="1">Email</option>
                                        <option value="2">Phone</option>
                                        <option value="3">SMS</option>
                                        <option value="4">Physical</option>
                                    </select>
                                    <small class="text-danger font-weight-bold err_communication_mode"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Appointment Time <span class="login-danger">*</span></label>

                                        <input type="time" class="form-control field appointment_time"
                                            name="appointment_time" id="appointment_time">

                                    <small class="text-danger font-weight-bold err_appointment_time"></small>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="input-block local-forms">
                                    <label>Reason</label>
                                    <textarea name="reason" id="reason" rows="5" class="form-control reason"></textarea>
                                    <small class="text-danger font-weight-bold err_reason"></small>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="input-block local-forms">
                                    <label>Description</label>
                                    <textarea name="description" id="description" rows="5" class="form-control description"></textarea>
                                    <small class="text-danger font-weight-bold err_description"></small>
                                </div>
                            </div>

                        </div>

                        @if (Auth::user()->hasPermissionTo('Create_intake'))
                        <div class="col-12">
                            <div class="doctor-submit text-end">
                                <button type="submit"
                                    class="btn btn-primary text-uppercase submit-form me-2">Create</button>
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

        $('#submitForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData($('#submitForm')[0]);

            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $('#loader').show()
                },
                url: "{{ route('business.IntakeForm.create') }}",
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

        function clearError() {
            $('#f_name').removeClass('is-invalid');
            $('.err_f_name').text('');

            $('#l_name').removeClass('is-invalid');
            $('.err_l_name').text('');

            $('#address').removeClass('is-invalid');
            $('.err_address').text('');

            $('#email').removeClass('is-invalid');
            $('.err_email').text('');

            $('#dob').removeClass('is-invalid');
            $('.err_dob').text('');

            $('#contact').removeClass('is-invalid');
            $('.err_contact').text('');

            $('#gender').removeClass('is-invalid');
            $('.err_gender').text('');

            $('#r_appointment_date').removeClass('is-invalid');
            $('.err_r_appointment_date').text('');

            $('#appointment_time').removeClass('is-invalid');
            $('.err_appointment_time').text('');

            $('#communication_mode').removeClass('is-invalid');
            $('.err_communication_mode').text('');

            $('#reason').removeClass('is-invalid');
            $('.err_reason').text('');

            $('#description').removeClass('is-invalid');
            $('.err_description').text('');

        }
    })
</script>
@endsection

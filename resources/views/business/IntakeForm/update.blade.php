@extends('layouts.business')

@section('title')
Manage Intake Form
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.IntakeForm.index') }}">Manage Intake Form</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Update Intake Form</li>
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
                                    <h4>Update Intake Form
                                </div>
                            </div>

                            <input type="hidden" name="id" value="{{$intake->id}}">

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="f_name">First Name <span class="text-danger">*</span> </label>
                                    <input type="text" name="f_name" value="{{$intake->f_name}}" class="form-control f_name"
                                        id="f_name" maxlength="190">
                                    <small class="text-danger font-weight-bold err_f_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="l_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="l_name" value="{{$intake->l_name}}" class="form-control l_name"
                                        id="l_name" maxlength="190">
                                    <small class="text-danger font-weight-bold err_l_name"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" value="{{$intake->address}}" class="form-control address number_only_val" maxlength="10"
                                        id="address" maxlength="190">
                                    <small class="text-danger font-weight-bold err_address"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{$intake->email}}" class="form-control email"
                                        id="email" maxlength="190">
                                    <small class="text-danger font-weight-bold err_email"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="contact">Contact <span class="text-danger">*</span></label>
                                    <input type="text" name="contact" value="{{$intake->contact}}" class="form-control contact number_only_val" maxlength="10"
                                        id="contact" maxlength="190">
                                    <small class="text-danger font-weight-bold err_contact"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" name="dob" value="{{$intake->dob}}" class="form-control dob number_only_val"
                                        id="dob" max="{{ date('Y-m-d') }}">
                                    <small class="text-danger font-weight-bold err_dob"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label for="appointment_time">Appointment Time <span class="text-danger">*</span></label>
                                    <input type="time" name="appointment_time" value="{{$intake->appointment_time ?? '12:00'}}" class="form-control appointment_time" id="appointment_time">
                                    <small class="text-danger font-weight-bold err_appointment_time"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms ">
                                    <label>Appointment Date <span class="login-danger">*</span></label>
                                    <input class="form-control" name="appointment_date" value='{{$intake->appointment_date}}' id="appointment_date" type="date"
                                        min="{{ date('Y-m-d') }}">
                                    <small class="text-danger font-weight-bold err_appointment_date"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Communication Mode <span class="login-danger">*</span></label>
                                    <select name="communication_mode" class="form-control communication_mode" id="communication_mode" required>
                                        <option value="" disabled>Select communication mode</option>
                                        <option value="1" {{ $intake->communication_mode == '1' ? 'selected' : '' }}>Email</option>
                                        <option value="2" {{ $intake->communication_mode == '2' ? 'selected' : '' }}>Phone</option>
                                        <option value="3" {{ $intake->communication_mode == '3' ? 'selected' : '' }}>SMS</option>
                                        <option value="4" {{ $intake->communication_mode == '4' ? 'selected' : '' }}>Physical</option>
                                    </select>
                                    <small class="text-danger font-weight-bold err_communication_mode"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label> Gender <span class="login-danger">*</span></label>
                                    <select name="gender" class="form-control gender" id="gender">
                                        <option value="" disabled>Select Gender</option>
                                        <option value="M" {{ $intake->gender == 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ $intake->gender == 'F' ? 'selected' : '' }}>Female</option>
                                        <option value="O" {{ $intake->gender == 'O' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <small class="text-danger font-weight-bold err_gender"></small>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="input-block local-forms">
                                    <label>Reason</label>
                                    <textarea name="reason" id="reason" rows="5" class="form-control reason">{{ $intake->reason }}</textarea>
                                    <small class="text-danger font-weight-bold err_reason"></small>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="input-block local-forms">
                                    <label>Description</label>
                                    <textarea name="description" id="description" rows="5" class="form-control description">{{ $intake->description }}</textarea>
                                    <small class="text-danger font-weight-bold err_description"></small>
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->hasPermissionTo('Update_intake'))
                        <div class="col-12">
                            <div class="doctor-submit text-end">
                                <button type="submit"
                                    class="btn btn-primary text-uppercase submit-form me-2">Update</button>
                            </div>
                        </div>
                 @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        })

        $('#submitForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData($('#submitForm')[0]);

            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $("#loader").show();
                },
                url: "{{ route('business.IntakeForm.update') }}",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $("#loader").hide();
                    errorClear()
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

        function errorClear()
        {
            $('#first_name').removeClass('is-invalid')
            $('.err_first_name').text('')

            $('#last_name').removeClass('is-invalid')
            $('.err_last_name').text('')

            $('#email').removeClass('is-invalid')
            $('.err_email').text('')

            $('#contact').removeClass('is-invalid')
            $('.err_contact').text('')

            $('#dob').removeClass('is-invalid')
            $('.err_dob').text('')

            $('#appointment_date').removeClass('is-invalid')
            $('.err_appointment_date').text('')

            $('#appointment_time').removeClass('is-invalid')
            $('.err_appointment_time').text('')

            $('#reason').removeClass('is-invalid')
            $('.err_reason').text('')

            $('#description').removeClass('is-invalid')
            $('.err_description').text('')

            $('#communication_mode').removeClass('is-invalid')
            $('.err_communication_mode').text('')
        }
    </script>
@endsection

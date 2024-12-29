@extends('layouts.business')

@section('title')
    Settings
@endsection

<style>
    .password-toggle {
      position: relative;
    }
    .password-toggle .fa-eye, .password-toggle .fa-eye-slash {
      position: absolute;
      top: 30%;
      right: 10px;
      cursor: pointer;
      z-index: 2;
    }
    .password-toggle input {
      padding-right: 35px;
    }
</style>

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:;">Settings </a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-table show-entire">
            <div class="card-body">

                <div class="card-body px-0 pb-0">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-3">
                        <nav id="navbar-example2" class="navbar bg-body-tertiary px-3 mb-3">
                            <ul class="nav nav-pills">
                              <li class="nav-item">
                                <a class="nav-link btn btn-primary text-uppercase submit-form me-2" href="#scrollspyHeading1">Notifications</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link btn btn-primary text-uppercase submit-form me-2" href="#scrollspyHeading2">Profile</a>
                              </li>
                            </ul>
                          </nav>
                          <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example p-3 rounded-2" tabindex="0">
                            <h4 id="scrollspyHeading1">Notifications</h4> <hr>
                            <form id="submitForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row col-12 gx-md-5">
                                    <h5>Do you want to send ... ?</h5>
                                    <input type="hidden" name="id" value="{{$business->notificationSettings->id}}">

                                    <div class="row" style="font-size: 13.5px;">
                                        <div class="col-lg-6 col-6 col-sm-6">
                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="rejected_mail">Rejected Mail</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->rejected_mail == 1 ? 'checked' : '' }} name="rejected_mail" role="switch" id="rejected_mail">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="confirmation_mail">Confirmation Mail</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->confirmation_mail == 1 ? 'checked' : '' }} name="confirmation_mail" role="switch" id="confirmation_mail">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="reminder_mail">Reminder Mail</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->reminder_mail == 1 ? 'checked' : '' }} name="reminder_mail" role="switch" id="reminder_mail">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="cancel_mail">Cancel Mail</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->cancel_mail == 1 ? 'checked' : '' }} name="cancel_mail" role="switch" id="cancel_mail">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="completed_mail">Completed Mail</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->completed_mail == 1 ? 'checked' : '' }} name="completed_mail" role="switch" id="completed_mail">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-6 col-sm-6">
                                            <div class="col-12 col-sm-6 form-group m-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="rejected_text">Rejected Text</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->rejected_text == 1 ? 'checked' : '' }} name="rejected_text" role="switch" id="rejected_text">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 form-group m-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="confirmation_text">Confirmation Text</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->confirmation_text == 1 ? 'checked' : '' }} name="confirmation_text" role="switch" id="confirmation_text">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="reminder_text">Reminder Text</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->reminder_text == 1 ? 'checked' : '' }} name="reminder_text" role="switch" id="reminder_text">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="cancel_text">Cancel Text</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->cancel_text == 1 ? 'checked' : '' }} name="cancel_text" role="switch" id="cancel_text">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <label class="form-label" for="completed_text">Completed Text</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="mx-2">NO</span>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" {{ $business->notificationSettings->completed_text == 1 ? 'checked' : '' }} name="completed_text" role="switch" id="completed_text">
                                                            </div>
                                                            <span class="mx-2">YES</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-end form-group mb-md-4 pb-3">
                                        <button type="submit" class="btn btn-primary text-uppercase submit-form me-2 mt-3" style="width: 150px">Update</button>
                                    </div>
                                </div>
                            </form>

                            {{-- profile --}}
                            <h4 id="scrollspyHeading2">Profile</h4> <hr>
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 text-center">
                                    <form action="" id="profileForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="upload mt-0 pr-md-4">
                                            <div class=" form-group mb-md-3 mx-3">
                                                <label class="form-label fs-5" for="">Profile</label> <br>
                                                @php
                                                $userProfile = Auth::user()->UserProfile;
                                                $profileImage = $userProfile && $userProfile->profile ? $userProfile->profile : asset('layout_style/img/profiles/avatar-01.jpg');
                                                @endphp
                                                    <img src="{{ $profileImage }}" border="0" width="122px" height="122px" class="stylist-image" align="center" />
                                                <br><br>
                                                <input type="file" name="file" class="form-control text-black" id="file">
                                                <small class="text-danger font-weight-bold err_file"></small>
                                            </div>
                                            {{-- <i class="btn text-uppercase btn-sm mb-4 flaticon-cloud-upload mr-1">Upload</i> --}}
                                        </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 mt-4 text-center">
                                        <div class="col-lg-12 col-12">
                                            <div class="input-block local-forms">
                                                <label for="exampleFormControlInput2">
                                                    First Name<span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="first_name" class="form-control"
                                                    value="{{ Auth::user()->first_name }}"
                                                    id="exampleFormControlInput2">
                                                <span class="text-danger font-weight-bold err_first_name"></span>
                                            </div>
                                        </div>
                                        <div class="input-block local-forms">
                                            <label for="exampleFormControlInput2">Last Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control"
                                                value="{{ Auth::user()->last_name }}" id="exampleFormControlInput2">
                                            <span class="text-danger font-weight-bold err_last_name"></span>
                                        </div>
                                        <div class="input-block local-forms">
                                            <label for="exampleFormControlInput2">Contact<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="contact" class="form-control contact"
                                                value="{{ Auth::user()->contact }}" maxlength="10"
                                                id="exampleFormControlInput2" required>
                                            <span class="text-danger font-weight-bold err_contact"></span>
                                        </div>
                                        <div class="input-block local-forms">
                                            <label for="exampleFormControlInput2">Email<span
                                                    class="text-danger">*</span></label>

                                                <input type="text" name="email" class="form-control text-black"
                                                    value="{{ Auth::user()->email }}" readonly
                                                    id="exampleFormControlInput2" required>
                                            <span class="text-danger font-weight-bold err_email"></span>
                                        </div>
                                        <div class="form-group mb-4 px-3 text-center text-sm-right">
                                            <button type="submit" class="btn btn-primary text-uppercase submit-form me-2" style="width: 200px">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 mt-4 text-center">
                                    <form action="" id="passwordForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-block local-forms password-toggle mb-4">
                                            <label for="oldPassword">Old Password<span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="oldPassword" name="old_password" placeholder="Enter old password">
                                            <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('oldPassword')"></i>
                                            <span class="text-danger font-weight-bold err_old_password"></span>
                                        </div>
                                        <div class="input-block local-forms password-toggle mb-4">
                                            <label for="newPassword">New Password<span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="newPassword" name="password" placeholder="Enter new password">
                                            <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('newPassword')"></i>
                                            <span class="text-danger font-weight-bold err_password"></span>
                                        </div>
                                        <div class="input-block local-forms password-toggle mb-4">
                                            <label for="confirmPassword">Confirm Password<span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm new password">
                                            <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('confirmPassword')"></i>
                                            <span class="text-danger font-weight-bold err_password_confirmation"></span>
                                        </div>

                                        <div class="col-lg-12 col-12 mb-5" id="submit_button_password">
                                            <div class="form-group mb-4 px-3 text-center text-sm-right">
                                                <button type="submit" class="btn btn-primary text-uppercase submit-form me-2" style="width: 200px">Update</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="col-lg-12 col-12 mb-5" id="disable_button_password" style="display: none">
                                        <div class="form-group mb-4 px-3 text-center text-sm-right">
                                            <button type="button"
                                                class="btn bg-gradient-primary text-uppercase btn-sm mb-0"
                                                style="width: 200px;"><i class="fas fa-spinner fa-spin"></i> Updating</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                url: "{{ route('settings.notification_update') }}",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $("#loader").hide();
                    // errorClear()
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
    </script>
        <script>
            $(document).ready(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#profileForm').submit(function(e) {
                    e.preventDefault();
                    let formData = new FormData($('#profileForm')[0]);

                    $.ajax({
                        type: "POST",
                        beforeSend: function() {
                            $('#submit_button_profile').css('display', 'none');
                            $('#disable_button_profile').css('display', 'block');

                        },
                        url: "{{ route('settings.profile_update') }}",
                        data: formData,
                        dataType: "JSON",
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(response) {
                            $('#submit_button_profile').css('display', 'block');
                            $('#disable_button_profile').css('display', 'none');

                            if (response.status == "val_error") {
                                $.each(response.errors, function(key, item) {
                                    if (key) {
                                        $('.err_' + key).text(item);
                                    } else {
                                        $('.err_' + key).text('');
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
                        }
                    });
                });

                $('#passwordForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData($('#passwordForm')[0]);

                $.ajax({
                    type: "POST",
                    beforeSend: function() {
                        $('#submit_button_password').css('display', 'none');
                        $('#disable_button_password').css('display', 'block');
                    },
                    url: "{{ route('settings.password_update') }}",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $('#submit_button_password').css('display', 'block');
                        $('#disable_button_password').css('display', 'none');

                        $('.err_old_password').text('');
                        $('.err_password').text('');
                        $('.err_password_confirmation').text('');

                        if (response.status == "val_error") {
                            $.each(response.errors, function(key, item) {
                                if (key) {
                                    $('.err_' + key).text(item);
                                } else {
                                    $('.err_' + key).text('');
                                }
                            });
                        } else if (response.status == "old_error") {
                            $('.err_old_password').text(response.message);
                        } else if (response.status == "error_password") {
                            $('.err_password').text(response.message);
                        } else {
                            $.confirm({
                                theme: 'modern',
                                columnClass: 'col-md-6 col-12 col-md-offset-4',
                                title: 'Success! ',
                                content: response.message,
                                type: 'green',
                                buttons: {
                                    confirm: {
                                        text: 'OK',
                                        btnClass: 'btn-150',
                                        action: function() {

                                        }
                                    },
                                }
                            });
                        }
                    },
                    statusCode: {
                        401: function() {
                            window.location.href = '{{ route('login') }}'; //or whatever is your login URI
                        },
                        419: function() {
                            window.location.href = '{{ route('login') }}'; //or whatever is your login URI
                        },
                    }
                });
});

            });
        </script>
    <script>

        $(document).on('click', '.toggle-password', function() {

            $(this).toggleClass("fa-eye fa-eye-slash");

            var input = $("#password");
        });

        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
              x.type = "text";
            } else {
              x.type = "password";
            }
        }

        </script>
    <script>
        function togglePasswordVisibility(fieldId) {
          const field = document.getElementById(fieldId);
          const icon = field.nextElementSibling;
          if (field.type === "password") {
            field.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          } else {
            field.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          }
        }
    </script>

        @if (session('success'))
            <script>
                $.confirm({
                    theme: 'modern',
                    columnClass: 'col-md-6 col-8 col-md-offset-4',
                    title: 'Success! ',
                    content: '{{ session('success') }}',
                    type: 'green',
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-150',
                            action: function() {}
                        },
                    }
                });
        </script>
        @endif
@endsection

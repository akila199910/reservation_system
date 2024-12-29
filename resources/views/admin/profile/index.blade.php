@extends('layouts.home')

@section('title')
    Profile
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
                <li class="breadcrumb-item"><a href="javascript:;">Profile </a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <div class="card position-sticky top-1 mt-1 mx-3">
            <div id="list-example" class="list-group border-radius-lg text-sm">
                <a class="nav-link btn-outline-primary text-uppercase text-sm" href="#profile-item">Profile</a>
                <a class="nav-link btn-outline-primary text-uppercase" href="#user-info-item">User Information</a>
                <a class="nav-link btn-outline-primary text-uppercase" href="#change-password-item">Change Password</a>
            </div>
        </div>
    </div>

    <div class="col-8">
      <div class="card">
        <div class="card-body">
            <div class="p-5">
                <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
                    <div class="doctor-table-blk">
                        <h3 class="text-uppercase" id="profile-item">Profile</h3>
                    </div>
                    <div class="" id="profile">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-auto col-12 mb-3">
                                <div class="avatar avatar-xl position-relative">
                                    <img src="{{ config('aws_url.url') . Auth::user()->UserProfile->profile }}" alt="profile"
                                        class="w-100 border-radius-lg shadow-sm" style="object-fit: cover; width: 80px; height: 80px;">
                                </div>
                            </div>



                        </div>
                    </div>
                    <div class="doctor-table-blk">
                        <h3 class="text-uppercase" id="user-info-item">User Informations</h3>
                    </div>
                    <form action="" id="profileForm" enctype="multipart/form-data">
                        @csrf
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">
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
                            <div class="input-block local-forms">
                                <div class="input-group">
                                    <label for="exampleFormControlInput2">Profile Picture<span
                                        class="text-danger">*</span></label>
                                    <input class="upload-path form-control" disabled />
                                    <div class="upload">
                                        <input type="file" name="image" class="form-control image" id="image"
                                            maxlength="190">
                                        <span class="custom-file-label" id="file-label">Choose File...</span>
                                    </div>
                                </div>
                                <small class="text-danger font-weight-bold err_image"></small>
                            </div>

                            <div class="form-group mb-4 px-3 text-center text-sm-right">
                                <button type="submit" class="btn btn-primary text-uppercase submit-form me-2" style="width: 200px">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="doctor-table-blk">
                        <h3 class="text-uppercase" id="change-password-item">Change Password</h3>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">
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





@endsection

@section('scripts')

    <script>
    //update profile information
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
                    $("#loader").show();
                },
                url: "{{ route('admin.profile.profile_update') }}",
                data: formData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $("#loader").hide();
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
                        url: "{{ route('admin.profile.password_update') }}",
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
    //toggle password

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

<script>
    // profile upload
    $(document).ready(function() {
        $(".profile-dimensions").hover(
            function() {
                $(this).addClass("hover-img");
            },
            function() {
                $(this).removeClass("hover-img");
            }
        );
        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.profile-pic').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".file-upload").on('change', function() {
            readURL(this);
        });

        $(".upload-button").on('click', function() {
            $(".file-upload").click();
        });
    });

</script>


@endsection

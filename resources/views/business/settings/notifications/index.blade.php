@extends('layouts.business')

@section('title')
  Manage  Notification Settings
@endsection

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:;"> Manage  Notification Settings </a></li>
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

                          <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example p-3 rounded-2" tabindex="0">
                            <div class="col mb-4">
                                <div class="doctor-table-blk">
                                    <h3 class="text-uppercase">Notifications</h3>
                                </div>
                            </div>
                            <form id="submitForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row col-12 gx-md-5">
                                    <h5>Do you want to send ... ?</h5>
                                    <input type="hidden" name="id" value="{{$business->notificationSettings->id}}">

                                    <div class="row" style="font-size: 13.5px;">

                                        {{-- notification emails --}}
                                        <div class="col-lg-6 col-6 col-sm-6">
                                            <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="gen-label" for="rejected_mail">Rejected Mail</label>
                                                    <div class="status-toggle">
                                                        <input type="checkbox" id="rejected_mail" name="rejected_mail" class="check" {{ $business->notificationSettings->rejected_mail == 1 ? 'checked' : '' }}>
                                                        <label for="rejected_mail" class="checktoggle">checkbox</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="gen-label" for="confirmation_mail">Confirmation Mail</label>
                                                    <div class="status-toggle">
                                                        <input type="checkbox" id="confirmation_mail" name="confirmation_mail" class="check" {{ $business->notificationSettings->confirmation_mail == 1 ? 'checked' : '' }}>
                                                        <label for="confirmation_mail" class="checktoggle">checkbox</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="gen-label" for="reminder_mail">Reminder Mail</label>
                                                    <div class="status-toggle">
                                                        <input type="checkbox" id="reminder_mail" name="reminder_mail" class="check" {{ $business->notificationSettings->reminder_mail == 1 ? 'checked' : '' }}>
                                                        <label for="reminder_mail" class="checktoggle">checkbox</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="gen-label" for="cancel_mail">Cancel Mail</label>
                                                    <div class="status-toggle">
                                                        <input type="checkbox" id="cancel_mail" name="cancel_mail" class="check" {{ $business->notificationSettings->cancel_mail == 1 ? 'checked' : '' }}>
                                                        <label for="cancel_mail" class="checktoggle">checkbox</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="gen-label" for="completed_mail">Completed Mail</label>
                                                    <div class="status-toggle">
                                                        <input type="checkbox" id="completed_mail" name="completed_mail" class="check" {{ $business->notificationSettings->completed_mail == 1 ? 'checked' : '' }}>
                                                        <label for="completed_mail" class="checktoggle">checkbox</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- notification texts --}}

                                        {{-- texts are only allowed if a business has a sanap_auth_key --}}
                                        @if ($business->snap_auth_key == true)
                                            <div class="col-lg-6 col-6 col-sm-6">
                                                <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <label class="gen-label" for="rejected_text">Rejected Text</label>
                                                        <div class="status-toggle">
                                                            <input type="checkbox" id="rejected_text" name="rejected_text" class="check" {{ $business->notificationSettings->rejected_text == 1 ? 'checked' : '' }}>
                                                            <label for="rejected_text" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <label class="gen-label" for="confirmation_text">Confirmation Text</label>
                                                        <div class="status-toggle">
                                                            <input type="checkbox" id="confirmation_text" name="confirmation_text" class="check" {{ $business->notificationSettings->confirmation_text == 1 ? 'checked' : '' }}>
                                                            <label for="confirmation_text" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <label class="gen-label" for="reminder_text">Reminder Text</label>
                                                        <div class="status-toggle">
                                                            <input type="checkbox" id="reminder_text" name="reminder_text" class="check" {{ $business->notificationSettings->reminder_text == 1 ? 'checked' : '' }}>
                                                            <label for="reminder_text" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <label class="gen-label" for="cancel_text">Cancel Text</label>
                                                        <div class="status-toggle">
                                                            <input type="checkbox" id="cancel_text" name="cancel_text" class="check" {{ $business->notificationSettings->cancel_text == 1 ? 'checked' : '' }}>
                                                            <label for="cancel_text" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-6 form-group mx-3 my-1 input-block select-gender">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <label class="gen-label" for="completed_text">Completed Text</label>
                                                        <div class="status-toggle">
                                                            <input type="checkbox" id="completed_text" name="completed_text" class="check" {{ $business->notificationSettings->completed_text == 1 ? 'checked' : '' }}>
                                                            <label for="completed_text" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-end form-group mb-md-4 pb-3">
                                        <button type="submit" class="btn btn-primary text-uppercase submit-form me-2 mt-3" style="width: 150px">Update</button>
                                    </div>
                                </div>
                            </form>


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

@endsection

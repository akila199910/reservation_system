@extends('layouts.home')

@section('title')
Manage businesses
@endsection

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.business') }}">Manage businesses</a></li>
                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                <li class="breadcrumb-item active">Create New Business</li>
            </ul>
        </div>
        <div class="col-sm-4 text-end">
            <a href="{{ route('admin.business') }}" class="btn btn-primary" style="width: 100px">Back</a>
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
                                <h4>Create New Business</h4>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block local-forms">
                                <label for="name">Business Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control name"
                                    id="name" maxlength="190">
                                <small class="text-danger font-weight-bold err_name"></small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block local-forms">
                                <label for="email">Business Email<span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control email"
                                    id="email" maxlength="190">
                                <small class="text-danger font-weight-bold err_email"></small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block local-forms">
                                <label for="contact">Business Contact<span class="text-danger">*</span></label>
                                <input type="text" name="contact" maxlength="10" class="form-control contact number_only_val"
                                    id="contact">
                                <small class="text-danger font-weight-bold err_contact"></small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block local-forms">
                                <label for="address">Business Address<span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control address" id="address" maxlength="190">
                                <small class="text-danger font-weight-bold err_address"></small>
                            </div>
                        </div>

                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longtitude">

                        <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block select-gender">
                                <label class="gen-label" for="status">Status<span class="text-danger">*</span> Inactive/Active</label>
                                <div class="status-toggle d-flex justify-content-between align-items-center">
                                    <input type="checkbox" id="status" name="status" class="check">
                                    <label for="status" class="checktoggle">checkbox</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block local-forms">
                                <label for="snap_auth_key">Business Snap Auth Key</label>
                                <input type="text" name="snap_auth_key" class="form-control snap_auth_key" id="snap_auth_key">
                                <small class="text-danger font-weight-bold err_snap_auth_key"></small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block select-gender">
                                <label class="gen-label" for="ibson_business">Ibson's Business<span class="text-danger">*</span> Yes/No</label>
                                <div class="ibson_business-toggle d-flex justify-content-between align-items-center">
                                    <input type="checkbox" id="ibson_business" name="ibson_business" class="check">
                                    <label for="ibson_business" class="checktoggle">checkbox</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-6" id="ibson_id_block" style="display: none;">
                            <div class="input-block local-forms">
                                <label for="ibson_id">Ibson's Id</label>
                                <input type="text" name="ibson_id" class="form-control ibson_id number_only_val"
                                    maxlength="10" id="ibson_id">
                                <small class="text-danger font-weight-bold err_ibson_id"></small>
                            </div>
                        </div>

                    </div>
                    <div class="col-12">
                        <div class="doctor-submit text-end">
                            <button type="submit" class="btn btn-primary text-uppercase submit-form me-2">
                                Create
                            </button>
                         </div>
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
            $('#business_list').hide();

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
                url: "{{ route('admin.business.create') }}",
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
            $('.err_name').text('')
            $('.err_email').text('')
            $('.err_contact').text('')
            $('.err_address').text('')
            $('.err_snap_auth_key').text('')
            $('.err_ibson_id').text('')
        }
    </script>

{{-- "Ibson's Id" input field will only be shown when the user selects the "Ibson's Business" checkbox. --}}
<script>
    document.getElementById('ibson_business').addEventListener('change', function() {
        var ibsonIdBlock = document.getElementById('ibson_id_block');
        if (this.checked) {
            ibsonIdBlock.style.display = 'block';
        } else {
            ibsonIdBlock.style.display = 'none';
        }
    });
</script>
@endsection

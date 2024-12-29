@extends('layouts.home')

@section('title')
    Manage Elements
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.elements') }}">Manage Elements </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Update Element</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('admin.elements') }}" class="btn btn-primary" style="width: 100px">Back</a>
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
                                    <h4>Update Element</h4>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{$element->id}}">
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Element Name <span class="login-danger">*</span></label>
                                    <input type="text" name="element_name" class="form-control element_name " id="element_name"
                                        maxlength="190" value="{{$element->layout_name}}">
                                    <small class="text-danger font-weight-bold err_element_name"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Element Type <span class="login-danger">*</span></label>
                                    <select name="element_type" class="form-control element_type select2 disabled" id="element_type">
                                        <option value=""></option>
                                        @foreach ($element_types as $item)
                                            <option value="{{ $item->id }}" {{$element->type_id == $item->id ? 'selected' : ''}}>{{ $item->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_element_type"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Normal Image <span class="login-danger">*</span></label>
                                    <input type="file" name="normal_image" class="form-control custom-file-input" id="normal_image">
                                    <small class="text-danger font-weight-bold err_normal_image"></small>
                                </div>
                                <div class="text-center">
                                    <img src="{{$element->normal_image}}" height="100px">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Checkedin Image <span class="login-danger">*</span></label>
                                    <input type="file" name="checkedin_image" class="form-control custom-file-input" id="checkedin_image">
                                    <small class="text-danger font-weight-bold err_checkedin_image"></small>
                                </div>
                                <div class="text-center">
                                    <img src="{{$element->checkedin_image}}" height="100px">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block select-gender">
                                    <label class="gen-label">Status Inactive/Active</label>
                                    <div class="status-toggle d-flex justify-content-between align-items-center">
                                        <input type="checkbox" id="status" {{$element->status == 1 ? 'checked' : ''}} name="status" class="check">
                                        <label for="status" class="checktoggle">checkbox</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="doctor-submit text-end">
                                    <button type="submit"
                                        class="btn btn-primary text-uppercase submit-form me-2">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#business_list').hide();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#submitForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData($('#submitForm')[0]);

                $.ajax({
                    type: "POST",
                    beforeSend: function() {
                        $('#loader').show()
                    },
                    url: "{{ route('admin.elements.update') }}",
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
                $('#element_name').removeClass('is-invalid');
                $('.err_element_name').text('');

                $('#element_type').removeClass('is-invalid');
                $('.err_element_type').text('');

                $('#normal_image').removeClass('is-invalid');
                $('.err_normal_image').text('');

                $('#checkedin_image').removeClass('is-invalid');
                $('.err_checkedin_image').text('');
            }

        });
    </script>
@endsection

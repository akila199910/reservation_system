@extends('layouts.business')

@section('title')
    Manage Tables
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.cafe') }}">Manage Tables </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Create new Table</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.cafe') }}" class="btn btn-primary" style="width: 100px">Back</a>
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
                                    <h4>Create New Table</h4>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Table Name <span class="login-danger">*</span></label>
                                    <input type="text" name="name" class="form-control name" id="name"
                                        maxlength="190">
                                    <small class="text-danger font-weight-bold err_name"></small>
                                </div>
                            </div>

                            {{-- <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block local-forms">
                                <label> Capacity <span class="login-danger">*</span></label>
                                <input type="text" name="capacity" value="0"
                                    class="form-control capacity decimal_val" id="capacity">
                                <small class="text-danger font-weight-bold err_capacity"></small>
                            </div>
                        </div> --}}

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label> Amount <span class="login-danger">*</span></label>
                                    <input type="text" name="amount" class="form-control amount decimal_val"
                                        id="amount">
                                    <small class="text-danger font-weight-bold err_amount"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Select Location <span class="login-danger">*</span></label>
                                    <select name="location" class="form-control location select2" id="location">
                                        <option value="">--Select Location--</option>
                                        @foreach ($locations as $item)
                                            <option value="{{ $item->id }}">{{ $item->location_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_location"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Select Section <span class="login-danger">*</span></label>
                                    <select name="perference_id" class="form-control perference_id select2 disabled"
                                        id="perference_id">
                                        <option value=""></option>
                                        @foreach ($perferences as $item)
                                            <option value="{{ $item->id }}">{{ $item->preference }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_perference_id"></small>
                                </div>
                            </div>

                            {{-- <div class="col-12 col-md-6 col-xl-6">
                            <div class="input-block local-forms">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control image" id="image"
                                    maxlength="190">
                                <small class="text-danger font-weight-bold err_image"></small>
                            </div>
                        </div> --}}
                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block local-forms">
                                    <label>Image</label>
                                    <input class="upload-path form-control" disabled />
                                    <div class="upload">
                                        <input type="file" name="image" class="form-control image" id="image"
                                            maxlength="190">
                                        <span class="custom-file-label" id="file-label">Choose File...</span>
                                    </div>
                                    <small class="text-danger font-weight-bold err_image"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-6">
                                <div class="input-block select-gender">
                                    <label class="gen-label">Status Inactive/Active</label>
                                    <div class="status-toggle d-flex justify-content-between align-items-center">
                                        <input type="checkbox" id="status" name="status" checked class="check">
                                        <label for="status" class="checktoggle">checkbox</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-12">
                                    <div class="form-heading">
                                        <h4 style="font-size: 14px">Select a table layout</h4>
                                    </div>
                                </div>

                                <div class="col-12 elements">
                                    @foreach ($element_types as $item)
                                        @php
                                            $btn_class = 'btn-outline-primary';
                                            if ($item->id == $first_element_type->id) {
                                                $btn_class = 'btn-primary';
                                            }
                                        @endphp
                                        <button
                                            class="btn {{ $btn_class }} mb-2 active_buttons _active_button_{{ $item->id }}"
                                            type="button"
                                            onclick="get_elements({{ $item->id }})">{{ $item->type_name }}</button>
                                    @endforeach

                                    <div class="col-12 mb-4">
                                        <div class="row element_section_div">
                                            @include('business.cafeTable.layout.create')
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if (Auth::user()->hasPermissionTo('Create_CafeTable'))
                                <div class="col-12">
                                    <div class="doctor-submit text-end">
                                        <button type="submit"
                                            class="btn btn-primary text-uppercase submit-form me-2">Save</button>
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
                    url: "{{ route('business.cafe.create') }}",
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
                $('#name').removeClass('is-invalid');
                $('.err_name').text('');

                // $('#capacity').removeClass('is-invalid');
                // $('.err_capacity').text('');

                $('#amount').removeClass('is-invalid');
                $('.err_amount').text('');

                $('#perference_id').removeClass('is-invalid');
                $('.err_perference_id').text('');

                $('#status').removeClass('is-invalid');
                $('.err_status').text('');

                $('#r_status').removeClass('is-invalid');
                $('.err_r_status').text('');

                $('#location').removeClass('is-invalid');
                $('.err_location').text('');

                $('.err_element').text('')
            }
        })

        function get_elements(id) {

            $('#loader').show()

            var data = {
                'element_type_id': id
            }

            $.ajax({
                type: "GET",
                url: "{{ route('business.cafe.get_elements') }}",
                data: data,
                success: function(response) {
                    $('#loader').hide()
                    $('.element_section_div').html('')
                    $('.element_section_div').html(response)

                    $('.active_buttons').removeClass('btn-primary');
                    $('.active_buttons').addClass('btn-outline-primary');
                    $('._active_button_' + id).addClass('btn-primary');
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
    </script>
@endsection

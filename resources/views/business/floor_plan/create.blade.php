@extends('layouts.business')

@section('title')
    Manage Floor Plans
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.cafe') }}">Manage Floor Plans </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Create new Floor Plan</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.floor_plan') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-heading">
                                    <h4>Create New Floor Plan</h4>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-xl-12">
                                <div class="input-block local-forms">
                                    <label>Select Section <span class="login-danger">*</span></label>
                                    <select name="section" class="form-control section select2 disabled" id="section">
                                        <option value=""></option>
                                        @foreach ($sections as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->preference . '(Location - ' . $item->location->location_name . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger font-weight-bold err_section"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-xl-12 _section_table_list">

                            </div>


                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('scripts')
    <script>
        var dropped_shape_data = {}; // Use an object for better key-value storage
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        })

        $('#section').change(function(e) {
            e.preventDefault();

            var data = {
                'section_id': $(this).val()
            }

            $('#loader').show()
            $.ajax({
                type: "GET",
                url: "{{ route('business.floor_plan.get_floor_layout') }}",
                data: data,
                success: function(response) {
                    $('#loader').hide()
                    $('._section_table_list').html('')
                    $('._section_table_list').html(response)
                },
                statusCode: {
                    401: function() {
                        window.location.href = '{{ route('login') }}';
                    },
                    419: function() {
                        window.location.href = '{{ route('login') }}';
                    },
                },
                error: function(data) {
                    console.error('Error fetching elements:', data);
                    someThingWrong();
                }
            });
        });
    </script>
@endsection

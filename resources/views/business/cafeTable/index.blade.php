@extends('layouts.business')

@section('title')
Manage Tables
@endsection

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:;">Manage Tables </a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card card-table show-entire">
            <div class="card-body">

                <div class="page-table-header mb-2">
                    <div class="row align-items-center mb-2">
                        <div class="col">
                            <div class="doctor-table-blk">
                                <h3 class="text-uppercase">Tables</h3>
                            </div>
                        </div>
                        <div class="col-auto text-end float-end ms-auto download-grp">
                            @if (Auth::user()->hasPermissionTo('Create_Users'))
                                <a href="{{ route('business.cafe.create.form') }}" class="btn btn-primary ms-2">
                                    +&nbsp;New Table
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-stripped " id="data_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Element Image</th>
                                <th>Name</th>
                                <th>Section</th>
                                <th>Location Name</th>
                                {{-- <th>Capacity</th> --}}
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
         var table;

            $(document).ready(function() {
                loadData()

                // $('#key_word').keyup(function(e) {
                //     table.clear();
                //     table.ajax.reload();
                //     table.draw();
                // });
            });

            function loadData() {
                table = $('#data_table').DataTable({
                    "stripeClasses": [],
                    "lengthMenu": [10, 20, 50],
                    "pageLength": 10,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('business.cafe') }}",
                        data: function(d) {
                            d.json = 1
                    }
                },
                columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'image',
                            name: 'image',
                            orderable: false,
                        },
                        {
                            data : 'element_image',
                            name: 'element_image',
                            orderable: false,
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: false,
                        },
                        {
                            data: 'preference',
                            name: 'preference',
                            orderable: false,
                        },
                        {
                            data: 'location',
                            name: 'location',
                            orderable: false,
                        },
                        // {
                        //     data: 'capacity',
                        //     name: 'capacity',
                        //     orderable: false,
                        // },
                        {
                            data: 'amount',
                            name: 'amount',
                            orderable: false,
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]

            });
        }

        function deleteConfirmation(id) {
            $.confirm({
                theme: 'modern',
                columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
                icon: 'far fa-question-circle text-danger',
                title: 'Are you Sure!',
                content: 'Do you want to Delete the Selected Table?',
                type: 'red',
                autoClose: 'cancel|10000',
                buttons: {
                    confirm: {
                        text: 'Yes',
                        btnClass: 'btn-green',
                        action: function() {
                            $("#loader").show();
                            var data = {
                                "_token": $('input[name=_token]').val(),
                                "id": id,
                            }
                            $.ajax({
                                type: "POST",
                                url: "{{ route('business.cafe.delete') }}",
                                data: data,
                                success: function(response) {
                                    $("#loader").hide();
                                    table.clear();
                                    table.ajax.reload();
                                    table.draw();
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
                    },

                    cancel: {
                        text: 'Cancel',
                        btnClass: 'btn-red',
                        action: function() {

                        }
                    },
                }
            });
        }


    </script>
@endsection

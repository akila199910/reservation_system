@extends('layouts.business')

@section('title')
    Manage Reservations
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:;">   Manage Reservations </a></li>
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
                                    <h3 class="text-uppercase">Reservations</h3>
                                </div>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                @if (Auth::user()->hasPermissionTo('Create_Reservation'))
                                    <a href="{{ route('business.reservation.create.form') }}" class="btn btn-primary ms-2">
                                        +&nbsp;New Reservation
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
                                    <th>Reservation_ID</th>
                                    <th>Client Name</th>
                                    <th>Client Contact</th>
                                    <th>Location Name</th>
                                    <th>Table Name</th>
                                    <th>Requested Date</th>
                                    <th>No of People</th>
                                    <th>No of Extra People</th>
                                    <th>Status</th>
                                    <th>Paid Status</th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Update Payment Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body _payment_update_div">

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
        });

        function loadData() {
            table = $('#data_table').DataTable({
                "stripeClasses": [],
                "lengthMenu": [10, 20, 50],
                "pageLength": 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('business.reservation') }}",
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
                        data: 'reservation_id',
                        name: 'reservation_id',
                        orderable: false
                    },
                    {
                        data: 'client_name',
                        name: 'client_info.name',
                        orderable: false,
                    },

                    {
                        data: 'client_contact',
                        name: 'client_info.contact',
                        orderable: false,
                    },
                    {
                        data: 'location_name',
                        name: 'location_info.location_name',
                        orderable: false,
                    },
                    {
                        data: 'table_name',
                        name: 'table_info.name',
                        orderable: false,
                    },
                    {
                        data: 'request_date',
                        name: 'request_date',
                        orderable: false
                    },
                    {
                        data: 'no_of_people',
                        name: 'no_of_people',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'extra_people',
                        name: 'extra_people',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'paid_status',
                        name: 'paid_status',
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

        function change_status(id, status) {

            if(status == 1)
            {
                var message = 'Do you want to Reject the Selected Reservation?';
            }

            if(status == 2)
            {
                var message = 'Do you want to Confirm the Selected Reservation?';
            }

            if(status == 3)
            {
                var message = 'Do you want to Cancel the Selected Reservation?';
            }

            if(status == 4)
            {
                var message = 'Do you want to Completed the Selected Reservation?';
            }

            $.confirm({
                theme: 'modern',
                columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
                icon: 'far fa-question-circle text-danger',
                title: 'Are you Sure!',
                content: message,
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
                                'status' : status
                            }
                            $.ajax({
                                type: "POST",
                                url: "{{ route('business.reservation.change_status') }}",
                                data: data,
                                success: function(response) {
                                    $("#loader").hide();

                                    if (response.status == false) {
                                        $.confirm({
                                            theme: 'modern',
                                            columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
                                            icon: 'fas fa-info-circle text-info',
                                            title: 'Failed!',
                                            content: response.message,
                                            type: 'red',
                                            autoClose: 'cancel|10000',
                                            buttons: {
                                                confirm: {
                                                    text: 'OK',
                                                    btnClass: 'btn-green',
                                                    action: function() {
                                                        location.href = response.route
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
                                    } else {

                                    }

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

        function deleteConfirmation(id) {
            $.confirm({
                theme: 'modern',
                columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
                icon: 'far fa-question-circle text-danger',
                title: 'Are you Sure!',
                content: 'Do you want to Delete the Selected Reservation?',
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
                                url: "{{ route('business.reservation.delete') }}",
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

        function change_pay_status(id)
        {
            $.confirm({
                theme: 'modern',
                columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
                icon: 'far fa-question-circle text-danger',
                title: 'Are you Sure!',
                content: 'Do you want to change the payment status to the Selected Reservation?',
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
                                url: "{{ route('business.reservation.get_payment_status') }}",
                                data: data,
                                success: function(response) {
                                    $("#loader").hide();
                                    $('._payment_update_div').html('')
                                    $('._payment_update_div').html(response)
                                    $('#paymentStatusModal').modal('show')
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

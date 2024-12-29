@extends('layouts.business')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:;">Dashboard </a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Today's Reservations --}}
    <div class="doctor-list-blk col-12">
        <div class="row pb-3">
            <div class="col-xl-6 col-md-6">
                <div class="doctor-table-blk">
                    @php
                        $todayDate = now()->format('Y-F-d');
                    @endphp
                    <h3 class="text-uppercase">Reservations - {{ $todayDate }}</h3>
                </div>
            </div>
        </div>

        <div class="row pb-3">
            @if (Auth::user()->hasPermissionTo('Read_Reservation'))
                <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current','')">
                @else
                    <div class="col-xl-4 col-md-6">
            @endif

            <div class="doctor-widget border-right-bg">
                <div class="doctor-box-icon flex-shrink-0">
                    <i class="fa-solid fa-utensils fa-xl" style="color: #ffffff;"></i>
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4>{{ $todayTotalReservations }}</h4>
                    <h5>Total</h5>
                </div>
            </div>
        </div>

        @if (Auth::user()->hasPermissionTo('Read_Reservation'))
            <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',0)">
            @else
                <div class="col-xl-4 col-md-6">
        @endif

        <div class="doctor-widget border-right-bg">
            <div class="doctor-box-icon flex-shrink-0">
                <i class="fa-solid fa-spinner fa-xl" style="color: #ffffff;"></i>
            </div>
            <div class="doctor-content dash-count flex-grow-1">
                <h4><span class="counter-up">{{ $todayPendingCount }}</span><span>/{{ $todayCurrentReservations }}</span>

                    <span class="status-yellow">
                        @if ($todayCurrentReservations > 0)
                            {{ number_format(($todayPendingCount / $todayCurrentReservations) * 100, 2) }}%
                        @else
                            0.00%
                        @endif
                    </span>
                </h4>
                <h5>Pending</h5>
            </div>
        </div>
    </div>

    @if (Auth::user()->hasPermissionTo('Read_Reservation'))
        <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',2)">
        @else
            <div class="col-xl-4 col-md-6">
    @endif

    <div class="doctor-widget">
        <div class="doctor-box-icon flex-shrink-0">
            <i class="fa-solid fa-check fa-xl" style="color: #ffffff;"></i>
        </div>
        <div class="doctor-content dash-count flex-grow-1">
            <h4><span class="counter-up">{{ $todayConfirmedCount }}</span><span>/{{ $todayCurrentReservations }}</span>
                <span class="status-blue">
                    @if ($todayCurrentReservations)
                        {{ number_format(($todayConfirmedCount / $todayCurrentReservations) * 100, 2) }}%
                    @else
                        0.00%
                    @endif
                </span>
            </h4>
            <h5>Confirmed </h5>
        </div>
    </div>
    </div>
    </div>
    <div class="row">
        @if (Auth::user()->hasPermissionTo('Read_Reservation'))
            <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',4)">
            @else
                <div class="col-xl-4 col-md-6">
        @endif

        <div class="doctor-widget border-right-bg">
            <div class="doctor-box-icon flex-shrink-0">
                <i class="fa-solid fa-list fa-xl" style="color: #ffffff;"></i>
            </div>
            <div class="doctor-content dash-count flex-grow-1">
                <h4><span class="counter-up">{{ $todayCompletedCount }}</span><span>/{{ $todayPastReservations }}</span>
                    <span class="status-green">
                        @if ($todayPastReservations)
                            {{ number_format(($todayCompletedCount / $todayPastReservations) * 100, 2) }}%
                        @else
                            0.00%
                        @endif
                    </span>
                </h4>
                <h5>Completed </h5>
            </div>
        </div>
    </div>

    @if (Auth::user()->hasPermissionTo('Read_Reservation'))
        <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',3)">
        @else
            <div class="col-xl-4 col-md-6">
    @endif

    <div class="doctor-widget border-right-bg">
        <div class="doctor-box-icon flex-shrink-0">
            <i class="fa-solid fa-xmark fa-xl" style="color: #ffffff;"></i>
        </div>
        <div class="doctor-content dash-count flex-grow-1">
            <h4><span class="counter-up">{{ $todayCancelledCount }}</span><span>/{{ $todayPastReservations }}</span>
                <span class="status-grey">
                    @if ($todayPastReservations)
                        {{ number_format(($todayCancelledCount / $todayPastReservations) * 100, 2) }}%
                    @else
                        0.00%
                    @endif
                </span>
            </h4>
            <h5>Cancelled </h5>
        </div>
    </div>
    </div>

    @if (Auth::user()->hasPermissionTo('Read_Reservation'))
        <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_reservation_List('current',1)">
        @else
            <div class="col-xl-4 col-md-6">
    @endif

    <div class="doctor-widget">
        <div class="doctor-box-icon flex-shrink-0">
            <i class="fa-solid fa-ban fa-xl" style="color: #ffffff;"></i>
        </div>
        <div class="doctor-content dash-count flex-grow-1">
            <h4><span class="counter-up">{{ $todayRejectedCount }}</span><span>/{{ $todayPastReservations }}</span>
                <span class="status-red">
                    @if ($todayPastReservations)
                        {{ number_format(($todayRejectedCount / $todayPastReservations) * 100, 2) }}%
                    @else
                        0.00%
                    @endif
                </span>
            </h4>
            <h5>Rejected </h5>
        </div>
    </div>
    </div>
    </div>

    <div class="row _current_reservation_div">

    </div>
    </div>

    {{-- All Reservations --}}
    <div class="doctor-list-blk col-12">
        <div class="row pb-3">
            <div class="col-xl-6 col-md-6">
                <div class="doctor-table-blk">
                    <h3 class="text-uppercase">All Reservations</h3>
                </div>
            </div>
        </div>
        <div class="row pb-3">
            @if (Auth::user()->hasPermissionTo('Read_Reservation'))
                <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_all_reservation_List('all','')">
                @else
                    <div class="col-xl-4 col-md-6">
            @endif
            <div class="doctor-widget border-right-bg">
                <div class="doctor-box-icon flex-shrink-0">
                    <i class="fa-solid fa-utensils fa-xl" style="color: #ffffff;"></i>
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4>{{ $totalReservations }}</h4>
                    <h5>Total</h5>
                </div>
            </div>
        </div>

        @if (Auth::user()->hasPermissionTo('Read_Reservation'))
            <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_all_reservation_List('all',0)">
            @else
                <div class="col-xl-4 col-md-6">
        @endif
        <div class="doctor-widget border-right-bg">
            <div class="doctor-box-icon flex-shrink-0">
                <i class="fa-solid fa-spinner fa-xl" style="color: #ffffff;"></i>
            </div>
            <div class="doctor-content dash-count flex-grow-1">
                <h4><span class="counter-up">{{ $pendingCount }}</span><span>/{{ $currentReservations }}</span>

                    <span class="status-yellow">
                        @if ($currentReservations > 0)
                            {{ number_format(($pendingCount / $currentReservations) * 100, 2) }}%
                        @else
                            0.00%
                        @endif
                    </span>
                </h4>
                <h5>Pending</h5>
            </div>
        </div>
    </div>

    @if (Auth::user()->hasPermissionTo('Read_Reservation'))
        <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_all_reservation_List('all',2)">
        @else
            <div class="col-xl-4 col-md-6">
    @endif
    <div class="doctor-widget">
        <div class="doctor-box-icon flex-shrink-0">
            <i class="fa-solid fa-check fa-xl" style="color: #ffffff;"></i>
        </div>
        <div class="doctor-content dash-count flex-grow-1">
            <h4><span class="counter-up">{{ $confirmedCount }}</span><span>/{{ $currentReservations }}</span>
                <span class="status-blue">
                    @if ($currentReservations)
                        {{ number_format(($confirmedCount / $currentReservations) * 100, 2) }}%
                    @else
                        0.00%
                    @endif
                </span>
            </h4>
            <h5>Confirmed </h5>
        </div>
    </div>
    </div>
    </div>
    <div class="row">
        @if (Auth::user()->hasPermissionTo('Read_Reservation'))
            <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_all_reservation_List('all',4)">
            @else
                <div class="col-xl-4 col-md-6">
        @endif
        <div class="doctor-widget border-right-bg">
            <div class="doctor-box-icon flex-shrink-0">
                <i class="fa-solid fa-list fa-xl" style="color: #ffffff;"></i>
            </div>
            <div class="doctor-content dash-count flex-grow-1">
                <h4><span class="counter-up">{{ $completedCount }}</span><span>/{{ $pastReservations }}</span>
                    <span class="status-green">
                        @if ($pastReservations)
                            {{ number_format(($completedCount / $pastReservations) * 100, 2) }}%
                        @else
                            0.00%
                        @endif
                    </span>
                </h4>
                <h5>Completed </h5>
            </div>
        </div>
    </div>
    @if (Auth::user()->hasPermissionTo('Read_Reservation'))
        <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_all_reservation_List('all',3)">
        @else
            <div class="col-xl-4 col-md-6">
    @endif
    <div class="doctor-widget border-right-bg">
        <div class="doctor-box-icon flex-shrink-0">
            <i class="fa-solid fa-xmark fa-xl" style="color: #ffffff;"></i>
        </div>
        <div class="doctor-content dash-count flex-grow-1">
            <h4><span class="counter-up">{{ $cancelledCount }}</span><span>/{{ $pastReservations }}</span>
                <span class="status-grey">
                    @if ($pastReservations)
                        {{ number_format(($cancelledCount / $pastReservations) * 100, 2) }}%
                    @else
                        0.00%
                    @endif
                </span>
            </h4>
            <h5>Cancelled </h5>
        </div>
    </div>
    </div>
    @if (Auth::user()->hasPermissionTo('Read_Reservation'))
        <div class="col-xl-4 col-md-6" style="cursor: pointer" onclick="get_all_reservation_List('all',1)">
        @else
            <div class="col-xl-4 col-md-6">
    @endif
    <div class="doctor-widget">
        <div class="doctor-box-icon flex-shrink-0">
            <i class="fa-solid fa-ban fa-xl" style="color: #ffffff;"></i>
        </div>
        <div class="doctor-content dash-count flex-grow-1">
            <h4><span class="counter-up">{{ $rejectedCount }}</span><span>/{{ $pastReservations }}</span>
                <span class="status-red">
                    @if ($pastReservations)
                        {{ number_format(($rejectedCount / $pastReservations) * 100, 2) }}%
                    @else
                        0.00%
                    @endif
                </span>
            </h4>
            <h5>Rejected </h5>
        </div>
    </div>
    </div>

    <div class="row _all_reservation_div">

    </div>
    </div>
    </div>

    {{-- Upcoming reservations table --}}
    @if (Auth::user()->hasPermissionTo('Read_Reservation'))
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table show-entire">
                    <div class="card-body">

                        <div class="page-table-header mb-2">
                            <div class="row align-items-center mb-2">
                                <div class="col">
                                    <div class="doctor-table-blk">
                                        <h3 class="text-uppercase">Upcoming Reservations</h3>
                                    </div>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    @if (Auth::user()->hasPermissionTo('Create_Reservation'))
                                        <a href="{{ route('business.reservation.create.form') }}"
                                            class="btn btn-primary ms-2">
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
                                        <th>Client Name</th>
                                        <th>Client Contact</th>
                                        <th>Table Name</th>
                                        <th>Requested Date</th>
                                        <th>No of People</th>
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
    @endif

    {{-- chart of this months' reservations --}}
    <div class="card">
        <div class="card-body">
            <div class="doctor-table-blk">
                @php
                    $currentMonthYear = now()->format('F Y');
                @endphp
                <h3 class="text-uppercase">Reservations - {{ $currentMonthYear }}</h3>
            </div>
            <div class="mt-3 mb-0 position-relative">
                <div>
                    <canvas id="myChart" width="600" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{-- upcoming reservation table --}}
    <script>
        var table;

        $(document).ready(function() {
            loadData();

            $('#filter').click(function() {
                table.ajax.reload();
            });
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
                        d.json = 1,
                        d.future = true,
                        d.order_by = 'ASC',
                        d.statuses = [0,2]
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // { data: 'ref_no', name: 'ref_no', orderable: false },
                    {
                        data: 'client_name',
                        name: 'client_info.name',
                        orderable: false
                    },
                    {
                        data: 'client_contact',
                        name: 'client_info.contact',
                        orderable: false
                    },
                    {
                        data: 'table_name',
                        name: 'table_info.name',
                        orderable: false
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

            if (status == 1) {
                var message = 'Do you want to Reject the Selected Reservation?';
            }

            if (status == 2) {
                var message = 'Do you want to Confirm the Selected Reservation?';
            }

            if (status == 3) {
                var message = 'Do you want to Cancel the Selected Reservation?';
            }

            if (status == 4) {
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
                                'status': status
                            }
                            $.ajax({
                                type: "POST",
                                url: "{{ route('business.reservation.change_status') }}",
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

        function get_reservation_List(day, status) {
            var data = {
                'day': day,
                'status': status,
                "_token": $('input[name=_token]').val(),
            }

            $('#loader').show()

            $.ajax({
                type: "POST",
                url: "{{ route('dashboard.get_reservation') }}",
                data: data,
                success: function(response) {
                    $('#loader').hide()

                    $('._current_reservation_div').html('')
                    $('._current_reservation_div').html(response)
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

        function get_all_reservation_List(day, status) {
            var data = {
                'day': day,
                'status': status,
                "_token": $('input[name=_token]').val(),
            }

            $('#loader').show()

            $.ajax({
                type: "POST",
                url: "{{ route('dashboard.get_reservation') }}",
                data: data,
                success: function(response) {
                    $('#loader').hide()

                    $('._all_reservation_div').html('')
                    $('._all_reservation_div').html(response)
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


    {{-- grapg of this months' reservations --}}
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "{{ route('dashboard.graph') }}",
                dataType: "JSON",
                success: function(response) {
                    const ctx = document.getElementById('myChart').getContext('2d');

                    // Define labels for each day of the month (1-31)
                    const labels = Array.from({
                        length: 31
                    }, (_, i) => i + 1);

                    // Extract data for each status
                    const pendingData = response.pending;
                    const rejectedData = response.rejected;
                    const confirmedData = response.confirmed;
                    const cancelledData = response.cancelled;
                    const completedData = response.completed;

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'PENDING',
                                    data: pendingData,
                                    backgroundColor: '#FFC107',
                                    borderColor: '#FFC107',
                                    borderWidth: 1
                                },
                                {
                                    label: 'REJECTED',
                                    data: rejectedData,
                                    backgroundColor: '#DC3545',
                                    borderColor: '#DC3545',
                                    borderWidth: 1
                                },
                                {
                                    label: 'CONFIRMED',
                                    data: confirmedData,
                                    backgroundColor: '#007BFF',
                                    borderColor: '#007BFF',
                                    borderWidth: 1
                                },
                                {
                                    label: 'CANCELLED',
                                    data: cancelledData,
                                    backgroundColor: '#6C757D',
                                    borderColor: '#6C757D',
                                    borderWidth: 1
                                },
                                {
                                    label: 'COMPLETED',
                                    data: completedData,
                                    backgroundColor: '#28A745',
                                    borderColor: '#28A745',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    barThickness: 20,
                                    maxBarThickness: 30,
                                    grid: {
                                        offset: true
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1, //show only whole numbers
                                        callback: function(value) {
                                            return Number.isInteger(value) ? value : null;
                                        }
                                    },
                                    min: 0
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            size: 10
                                        },
                                        color: '#333'
                                    }
                                },
                                tooltip: {
                                    enabled: true
                                }
                            },
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 10,
                                    top: 5,
                                    bottom: 5
                                }
                            }
                        }
                    });
                },
                statusCode: {
                    401: function() {
                        window.location.href = '{{ route('login') }}';
                    },
                    419: function() {
                        window.location.href = '{{ route('login') }}';
                    }
                }
            });
        });
    </script>
@endsection

@extends('layouts.business')

@section('title')
    Manage Reports
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:;">Manage Reports</a></li>
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
                                    <h3 class="text-uppercase">Reports</h3>
                                </div>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <button href="" class="btn btn-primary ms-2" id="submit">+&nbsp; Download</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="submitForm" method="POST" enctype="multipart/form-data"
                            action="{{ route('cafe.reports.download') }}">
                            @csrf
                            <div class="row p-4">
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="input-block local-forms">
                                        <label>Select Table</label>
                                        <select name="cafe" class="form-control cafe select2" id="cafe">
                                            <option value="">--All table--</option>
                                            @foreach ($cafes as $cafe)
                                                <option value="{{ $cafe->id }}">{{ $cafe->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger font-weight-bold err_cafe"></small>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-md-6 col-xl-4">
                                <div class="input-block local-forms">
                                    <label>Select Date Range </label>
                                    <select class="form-control select2" id="daterange" name="daterange">
                                        <option value=""></option>
                                        <option value="weekToDate">This Week</option>
                                        <option value="ofLast7Days">Last 7 days</option>
                                        <option value="ofLastWeek">Last Week</option>
                                        <option value="monthToDate">This Month</option>
                                        <option value="ofLast30Days">Last 30 days</option>
                                        <option value="ofLastMonth">Last Month</option>
                                    </select>
                                    <small class="text-danger font-weight-bold err_daterange"></small>
                                </div>
                            </div> --}}
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="input-block local-forms">
                                        <label>Select status </label>
                                        <select class="form-control select2" id="status" name="status">
                                            <option value="">--All status--</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Rejected</option>
                                            <option value="2">Confirmed</option>
                                            <option value="3">Cancelled</option>
                                            <option value="4">Completed</option>
                                        </select>
                                        <small class="text-danger font-weight-bold err_status"></small>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="input-block local-forms">
                                        <label>From </label>
                                        <input class="form-control" type="date" name="from" id="from">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="input-block local-forms">
                                        <label>To </label>
                                        <input class="form-control" type="date" name="to" id="to">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-9 text-end"></div>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 text-end">
                                    <button type="button" class="btn btn-lg btn-block btn-primary rest_button mb-3"
                                        id="reset_button">RESET</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-stripped" id="data_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Client Contact</th>
                                    <th>Location Name</th>
                                    <th>Table Name</th>
                                    <th>Requested Date</th>
                                    <th>No of People</th>
                                    <th>Status</th>
                                    <th>Paid Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Monthly Completed Reservations</h5>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Completed Table Reservation</h5>
                </div>
                <div class="card-body">
                    <canvas id="compleate_cafe_table"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var table;

        $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadData();

            $('#cafe, #from, #to, #status').on('change', function() {
                table.draw();
            });

            $('#reset_button').click(function(e) {
                e.preventDefault();

                $('#cafe').val('').change()
                $('#from').val('').change()
                $('#to').val('').change()
                $('#status').val('').change()
            });

            $('#submit').on('click', function() {
                $('#submitForm').submit();
            });

            // $('#submit').on('click', function() {
            //     var form = $('#submitForm')[0];
            //     var formData = new FormData(form);

            //         $.ajax({
            //         type: "POST",
            //         beforeSend: function() {
            //             $('#loader').show();
            //         },
            //         url: "{{ route('cafe.reports.download') }}",
            //         data: formData,
            //         dataType: "JSON",
            //         contentType: false,
            //         cache: false,
            //         processData: false,
            //         success: function(response) {
            //             $('#loader').hide();
            //             if (response.status == false) {
            //                 $.each(response.message, function(key, item) {
            //                     if (key) {
            //                         $('.err_' + key).text(item);
            //                         $('#' + key).addClass('is-invalid');
            //                     }
            //                 });
            //             } else {
            //                 window.location.href = response.download_url;
            //             }
            //         },
            //         statusCode: {
            //             401: function() {
            //                 window.location.href =
            //                     '{{ route('login') }}'; //or what ever is your login URI
            //             },
            //             419: function() {
            //                 window.location.href =
            //                     '{{ route('login') }}'; //or what ever is your login URI
            //             },
            //         },
            //         error: function(data) {
            //             someThingWrong();
            //         }
            //     });
            // });

            $.ajax({
                type: "GET",
                url: "{{ route('graph') }}",
                dataType: "JSON",
                success: function(response) {
                    const ctx = document.getElementById('myChart').getContext('2d');
                    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                        'August', 'September', 'October', 'November', 'December'
                    ];
                    const data = labels.map((_, index) => response.monthlyCounts[index + 1] || 0);
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Monthly Completed Reservation',
                                data: data,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                fill: true,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                },
                                y: {
                                    beginAtZero: true,
                                },
                            },
                        },
                    });
                },
            });
            // for cafetable
            $.ajax({
                type: "GET",
                url: "{{ route('graph.table') }}",
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        const ctx = document.getElementById('compleate_cafe_table').getContext('2d');

                        const labels = Object.keys(response.tablelyCounts);
                        const data = Object.values(response.tablelyCounts);

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Completed Table Reservation',
                                    data: data,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                    },
                                    y: {
                                        beginAtZero: true,
                                    },
                                },
                            },
                        });
                    }
                },
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
                    url: "{{ route('cafe.reports') }}",
                    data: function(d) {
                        d.json = 1;
                        d.cafe = $('#cafe').val();
                        // d.daterange = $('#daterange').val();
                        d.status = $('#status').val();
                        d.from = $('#from').val();
                        d.to = $('#to').val();
                        console.log(d.from)
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
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
                        data: 'location_name',
                        name: 'location_info.location_name',
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
                    }
                ],
                "initComplete": function(settings, json) {
                    $('#data_table_filter').addClass('d-none');
                }
            });
        }
    </script>
@endsection

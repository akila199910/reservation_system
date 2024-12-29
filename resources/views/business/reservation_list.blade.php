<div class="col-lg-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-5">
    <div class="card card-table show-entire">
        <div class="card-body">


            @php

                $status_name = 'All';
                if ($status == 0 && $status != null) {
                    $status_name = 'Pending';
                }

                if ($status == 1 && $status != null) {
                    $status_name = 'Rejected';
                }

                if ($status == 2 && $status != null) {
                    $status_name = 'Confirmed';
                }

                if ($status == 3 && $status != null) {
                    $status_name = 'Cancelled';
                }

                if ($status == 4 && $status != null) {
                    $status_name = 'Completed';
                }
            @endphp

            <div class="page-table-header mb-2">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <div class="doctor-table-blk">
                            <h3 class="text-uppercase">
                                {{ $current == true ? 'Today ' . $status_name . ' Reservations ' :  $status_name . ' Reservations' }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-stripped " id="reservation_data_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ref No</th>
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

<script>
    var reservation_table;
    $(document).ready(function() {
        loadReservationData();
    });

    function loadReservationData() {
        reservation_table = $('#reservation_data_table').DataTable({
            "stripeClasses": [],
            "lengthMenu": [10, 20, 50],
            "pageLength": 10,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dashboard.get_reservation_list') }}",
                data: function(d) {
                    d.json = 1,
                        d.current = '{{ $current }}',
                        d.status = '{{ $status }}'
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ref_no',
                    name: 'ref_no',
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
</script>

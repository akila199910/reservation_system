@extends('layouts.business')

@section('title')
    Manage Reservations
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.clients') }}">Manage Reservations</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Reservation Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.reservation') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="doctor-table-blk mb-4 pt-2">
                                            <h3 class="text-uppercase">Reservation Details</h3>
                                        </div>
                                        <div class="row">
                                            <h4 class="mt-3 mb-2">Client Details</h4>
                                            <hr>
                                            <div class="col-12 col-md-12 col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600;font-size: 14px !important ">First
                                                                Name </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['client_info']['first_name'] }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">Last
                                                                Name </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['client_info']['last_name'] }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">Email
                                                            </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important;">
                                                                {{ $data['client_info']['email'] }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important;">Contact
                                                            </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['client_info']['contact'] }} </p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>


                                            <h4 class="mt-3 mb-2">Reservation Details</h4>
                                            <hr>
                                            <div class="col-12 col-md-12 col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600;font-size: 14px !important ">Location
                                                            </p>
                                                        </span>
                                                    </div>

                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['location_name'] }} </p>
                                                        </span>
                                                    </div>

                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">Table
                                                            </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['table_info']['name'] }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">
                                                                Requested Date </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ date('jS M, Y', strtotime($data['request_date'])) }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">Start
                                                                Time </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ date('h:i A', strtotime($data['start_time'])) }}
                                                            </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">End Time
                                                            </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ date('h:i A', strtotime($data['end_time'])) }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">No of
                                                                People </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['no_of_people'] }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">No of
                                                                Extra People </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['extra_people'] }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">
                                                                Reservation Status </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">{{ $data['status'] }}
                                                            </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">Payment
                                                                Status </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ $data['paid_status'] }} </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-5 col-12 col-md-5">
                                                        <span class="d-flex">
                                                            <p style="font-weight: 600; font-size: 14px !important">
                                                                Reservation Note </p>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-7 col-12 col-md-7">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">{!! nl2br(e(wordwrap($data['reservation_note'], 30, "\n", true))) !!}
                                                            </p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-12 col-md-12 col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-9 col-12 col-md-9 text-end">
                                                        <span class="">
                                                            <p style="font-weight: 600;font-size: 14px !important ">Table
                                                                Amount </p>
                                                        </span>
                                                        <span class="">
                                                            <p style="font-weight: 600; font-size: 14px !important">
                                                                Discount Amount </p>
                                                        </span>
                                                        <span class="">
                                                            <p style="font-weight: 600; font-size: 14px !important">Service
                                                                Charge </p>
                                                        </span>
                                                        <span class="">
                                                            <p style="font-weight: 600; font-size: 14px !important">Extra
                                                                People Charge </p>
                                                        </span>
                                                        <span class="">
                                                            <p style="font-weight: 600; font-size: 14px !important">Total
                                                                Amount </p>
                                                        </span>
                                                    </div>

                                                    <div class="col-sm-3 col-12 col-md-3">
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ number_format($data['amount'], 2, '.', '') }}
                                                            </p>
                                                        </span>
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ number_format($data['discount'], 2, '.', '') }}
                                                            </p>
                                                        </span>
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ number_format($data['service_amount'], 2, '.', '') }}
                                                            </p>
                                                        </span>
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important">
                                                                {{ number_format($data['extra_amount'], 2, '.', '') }} </p>
                                                        </span>
                                                        <span class="d-flex">
                                                            <p style="font-size: 14px !important; font-weight: 600">
                                                                {{ number_format($data['final_amount'], 2, '.', '') }} </p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

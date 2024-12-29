@extends('layouts.payment')

@section('title')
Manage Reservation Payment Already Paid
@endsection

@section('content')
    <div class="container">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-12"></div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 mt-5">

                    <div class="doctor-personals-grp">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-detail text-center">
                                    <h4 class="mb-3 text-uppercase">Reservation Details</h4>
                                    <hr>
                                </div>
                                <div class="about-me-list">
                                    <ul class="list-space">
                                        <li>
                                            <h4>Referene Number</h4>
                                            <span class="text-black">: {{ $reservation->ref_no }}</span>
                                        </li>
                                        <li>
                                            <h4>Customer Name</h4>
                                            <span class="text-black">: {{ $reservation->client_info->name }}</span>
                                        </li>
                                        <li>
                                            <h4>Customer Contact</h4>
                                            <span class="text-black">: {{ $reservation->client_info->contact }}</span>
                                        </li>

                                        <li>
                                            <h4>Requested Date</h4>
                                            <span class="text-black">:
                                                {{ date('jS, M Y', strtotime($reservation->request_date)) }}</span>
                                        </li>

                                        <li>
                                            <h4>Requested Table</h4>
                                            <span class="text-black">: {{ $reservation->table_info->name }}</span>
                                        </li>

                                        <li>
                                            <h4>Number of People</h4>
                                            <span class="text-black">: {{ $reservation->no_of_people }}</span>
                                        </li>

                                        <li>
                                            <h4>Amount</h4>
                                            <span class="text-black">:
                                                {{ number_format($reservation->amount + $reservation->extra_amount + $reservation->service_amount - $reservation->discount, 2, '.', '') }}</span>
                                        </li>
                                    </ul>

                                    <div class="text-center mt-3">
                                        <i class="fa fa-check-circle text-success" style="font-size: 5em"></i>
                                        <h3 class="mb-3 mt-2 text-success text-uppercase">Review Already Done.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-12"></div>
            </div>
        </div>
    </div>
@endsection

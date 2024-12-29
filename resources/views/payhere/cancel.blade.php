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

                                <div class="about-me-list">


                                    <div class="text-center mt-3">
                                        <i class="fa fa-times-circle text-danger" style="font-size: 5em"></i>
                                        <h3 class="mb-3 mt-2 text-danger text-uppercase">Payment Cancelled.</h3>
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

@extends('layouts.payment')

@section('title')
Manage Reservation Payment
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
                                    <form method="POST" class="text-left" id="submitForm" action="{{env('PAYHERE_URL')}}" enctype="multipart/form-data">
                                        @csrf
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

                                        @php
                                            $merchant_id = env('PAYHERE_MERCHANT_ID');
                                            $order_id = $reservation->ref_no;
                                            $amount = ($reservation->amount + $reservation->extra_amount + $reservation->service_amount - $reservation->discount);
                                            $currency = 'LKR';
                                            $merchant_secret = env('PAYHERE_MERCHANT_SECRET');

                                            $hash = strtoupper(
                                                md5(
                                                    $merchant_id .
                                                    $order_id .
                                                    number_format($amount, 2, '.', '') .
                                                    $currency .
                                                    strtoupper(md5($merchant_secret))
                                                )
                                            );
                                        @endphp

                                        <input type="hidden" name="merchant_id" value="{{$merchant_id}}" id="merchant_id">    <!-- Replace your Merchant ID -->
                                        <input type="hidden" name="return_url" value="{{url('/payment_success')}}" id="return_url">
                                        <input type="hidden" name="cancel_url" value="{{url('/payment_cancel')}}" id="cancel_url">
                                        <input type="hidden" name="notify_url" value="{{url('/payment_approved')}}" id="notify_url">

                                        <!-- Payment Feild -->
                                        <input type="hidden" class="form-control input-style" readonly name="first_name" value="{{$reservation->client_info->first_name}}" id="first_name" placeholder="Name">
                                        <input type="hidden" class="form-control input-style" readonly name="last_name" value="{{$reservation->client_info->last_name}}" id="last_name" placeholder="Name">
                                        <input type="hidden" class="form-control input-style" readonly name="email" value="{{$reservation->client_info->email}}" id="email" placeholder="Name">
                                        <input type="hidden" class="form-control input-style" readonly name="phone" value="{{$reservation->client_info->contact}}" id="phone" placeholder="Name">
                                        <input type="hidden" class="form-control input-style" readonly value="{{number_format($amount, 2, '.', '') }}" id="amount_view" placeholder="Name">

                                        <input type="hidden" readonly class="form-control"  name="address" value="{{$reservation->client_info->client_profile->street_address ?? 'Main Street'}}" id="address" placeholder="Name">
                                        <input type="hidden" readonly class="form-control"  name="city" value="{{$reservation->client_info->client_profile->city ?? 'Colombo'}}" id="city" placeholder="Name">
                                        <input type="hidden" readonly class="form-control"  name="country" value="Sri Lanka" id="country" placeholder="Name">
                                        <input type="hidden" readonly class="form-control"  name="order_id" value="{{$order_id}}" id="order_id" placeholder="Name">
                                        <input type="hidden" readonly class="form-control"  name="items" value="{{$reservation->ref_no}}" id="items" placeholder="Name">
                                        <input type="hidden" readonly class="form-control"  name="amount" value="{{number_format($amount, 2, '.', '')}}" id="amount" placeholder="Name">
                                        <input type="hidden" readonly class="form-control"  name="currency" value="{{$currency}}" id="currency" placeholder="Name">
                                        <input type="hidden" readonly class="form-control"  name="hash" value="{{$hash}}" id="hash" placeholder="Name">

                                        <div class="input-block login-btn mt-4">
                                            <button style="height: 45px" class="btn btn-lg btn-primary btn-block" type="submit">Pay Now</button>
                                        </div>
                                    </form>
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

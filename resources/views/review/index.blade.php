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
                                    <form method="POST" class="text-left" id="submitForm" enctype="multipart/form-data">
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
                                        <hr>
                                        <div class="card-body card-buttons">
                                            <p>Rate Our Service</p>
                                            <input type="hidden" name="review_id" value="{{$reservation->id}}">
                                            <hr>
                                            <div class="rating rating-default"></div>
                                            <span class="err_score text-danger"></span>
                                            <div class="input-block mt-3">
                                                <textarea name="review_message" class="form-control review_message" rows="3" id="" placeholder="Enter your Review Here ..."></textarea>
                                                <span class="err_review_message text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="input-block login-btn mt-4 submit_button">
                                            <button style="height: 45px" class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
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

@section('scripts')
    <script>
        $('#submitForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData($('#submitForm')[0]);

            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $('#loader').show()
                },
                url: "{{ route('reservation.reviewus.submit_review') }}",
                data: formData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('#loader').hide()
                    console.log(response);
                    clearError();

                    if (response.status == false) {
                        $.each(response.errors, function(key, item) {
                            if (key) {
                                $('.err_' + key).text(item);
                                $('.' + key).addClass('border-danger');
                            }
                        });
                    } else {
                        successPopup(response.message, '')
                        $('.submit_button').hide()
                    }
                },
                error: function(data) {
                    console.log(data);
                    $('#loader').hide()
                    alert('Something went to wrong')
                }
            });
        });

        function clearError() {
            $('.err_score').text('');
            $('.score').removeClass('border-danger');

            $('.err_review_message').text('');
            $('.review_message').removeClass('border-danger');
        }
    </script>
@endsection

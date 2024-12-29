<form id="updatePaymentStatus" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $reservation->id }}">

    <div class="col-12 col-md-12 col-xl-12 mt-4">
        <div class="input-block local-forms">
            <label>Select Payment Type</label>
            <select name="payment_type" id="payment_type" class="form-control payment_type" style="height: 40px !important">
                <option value="0">--Select a payment method--</option>
                <option value="1" {{ $reservation->payment_type == 1 ? 'selected' : '' }}>
                    Direct Paid</option>
                <option value="2" {{ $reservation->payment_type == 2 ? 'selected' : '' }}>
                    Online Paid</option>
            </select>

            <small class="text-danger font-weight-bold err_payment_type"></small>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="doctor-submit text-end">
            <button type="submit" class="btn btn-primary text-uppercase submit-form me-2">Update</button>
        </div>
    </div>
</form>

<script>
    $('#updatePaymentStatus').submit(function(e) {
        e.preventDefault();
        let formData = new FormData($('#updatePaymentStatus')[0]);

        $.ajax({
            type: "POST",
            beforeSend: function() {
                $('#loader').show()
            },
            url: "{{ route('business.reservation.update_payment_status') }}",
            data: formData,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $('#loader').hide()

                $('.err_payment_type').text('');

                if (response.status == false) {
                    $.each(response.message, function(key, item) {
                        if (key) {
                            $('.err_' + key).text(item)
                        }
                    });
                } else {
                    successPopup(response.message, '')
                    $('#paymentStatusModal').modal('hide')
                    calendar.refetchEvents();
                    calendar.refetchResources();
                    close_popup()
                }
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
    });
</script>

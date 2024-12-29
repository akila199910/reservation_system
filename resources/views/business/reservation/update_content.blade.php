<div class="row">

    @if (count($tables))
    <div class="col-sm-12 mt-4 mb-4">
        <h4>Select Table</h4>
        <hr>
        <div class="radio-group">
            @foreach ($tables as $key => $item)
                <div class="custom-radio-table">
                    <input type="radio" class="table" id="table_{{ $item->id }}" name="table"
                        value="{{ $item->ref_no }}" {{$item->id == $reservation->cafetable_id ? 'checked' : ''}}>
                    <label for="table_{{ $item->id }}">
                        <img src="{{ config('aws_url.url').$item->image }}" alt="table {{ $item->id }}">
                        <span>{{ ucwords($item->name) }}</span>
                    </label>
                </div>
            @endforeach

        </div>

        <small class="text-danger font-weight-bold err_table"></small>
    </div>

    <div class="col-12 col-md-4 col-xl-4">
        <div class="input-block local-forms">
            <label>No of People <span class="login-danger">*</span></label>
            <select name="no_of_people" class="form-control no_of_people select2" id="no_of_people">
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{$i}}" {{$i == $reservation->no_of_people ? 'selected' : ''}}>{{$i}}</option>
                @endfor
            </select>
            <small class="text-danger font-weight-bold err_no_of_people"></small>
        </div>
    </div>

    <div class="col-12 col-md-4 col-xl-4">
        <div class="input-block local-forms">
            <label>No of Extra People</label>
            <input type="text" name="no_of_extra_people" id="no_of_extra_people"
                class="no_of_extra_people form-control number_only_val" value="{{$reservation->extra_people}}">
            <small class="text-danger font-weight-bold err_no_of_extra_people"></small>
        </div>
    </div>

    <div class="col-12 col-md-12 col-xl-12">
        <div class="input-block local-forms">
            <label>Reservation Note</label>
            <textarea name="reservation_note" id="reservation_note" rows="5" class="form-control reservation_note">{{$reservation->reservation_note}}</textarea>
            <small class="text-danger font-weight-bold err_reservation_note"></small>
        </div>
    </div>

    <div class="col-12 col-md-8 col-xl-8"></div>
    <div class="col-12 col-md-4 col-xl-4">
        <div class="row">

            <div class="col-12 col-md-12 col-xl-12">
                <div class="input-block local-forms">
                    <label>Table Amount <span class="login-danger">*</span></label>
                    <input type="text" name="table_amount" value="{{$reservation->amount}}"
                        class="form-control table_amount decimal_val" id="table_amount">
                    <small class="text-danger font-weight-bold err_table_amount"></small>
                </div>
            </div>

            <div class="col-12 col-md-12 col-xl-12">
                <div class="input-block local-forms">
                    <label>Discount Amount </label>
                    <input type="text" name="discount_amount" value="{{$reservation->discount}}"
                        class="form-control discount_amount decimal_val" id="discount_amount">
                    <small class="text-danger font-weight-bold err_discount_amount"></small>
                </div>
            </div>

            <div class="col-12 col-md-12 col-xl-12">
                <div class="input-block local-forms">
                    <label>Service Charge </label>
                    <input type="text" name="service_charge" value="{{$reservation->service_amount}}"
                        class="form-control service_charge decimal_val" id="service_charge">
                    <small class="text-danger font-weight-bold err_service_charge"></small>
                </div>
            </div>

            <div class="col-12 col-md-12 col-xl-12">
                <div class="input-block local-forms">
                    <label>Extra People Charge </label>
                    <input type="text" name="extra_people_charge" value="{{$reservation->extra_amount}}"
                        class="form-control extra_people_charge decimal_val" id="extra_people_charge">
                    <small class="text-danger font-weight-bold err_extra_people_charge"></small>
                </div>
            </div>

            <div class="col-12 col-md-12 col-xl-12">
                <div class="input-block local-forms">
                    <label>Total Amount<span class="login-danger">*</span></label>
                    <input type="text" readonly name="total_amount" value="{{$reservation->final_amount}}"
                        class="form-control total_amount decimal_val" id="total_amount">
                    <small class="text-danger font-weight-bold err_total_amount"></small>
                </div>
            </div>
        </div>
    </div>
    @else

    @endif

</div>

<script>
    $(document).ready(function() {
        $('.select2').select2()

        //number only validation
        $(".number_only_val").on("input", function(evt) {
            var self = $(this);
            self.val(self.val().replace(/\D/g, ""));
            if ((evt.which < 48 || evt.which > 57)) {
                evt.preventDefault();
            }
        });

        //Decimal validation
        $(".decimal_val").on("input", function(evt) {
            var self = $(this);
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which >
                    57)) {
                evt.preventDefault();
            }
        });
    });

    $('input[name="table"]').change(function() {
        // Get the value of the selected radio button
        $('#loader').show()

        var data = {
            'table_id': $(this).val()
        }

        $.ajax({
            type: "POST",
            url: "{{ route('business.reservation.table_details') }}",
            data: data,
            dataType: "JSON",
            success: function(response) {
                $('#loader').hide()

                clearError()

                $('#table_amount').val('0.00')
                clearAmountFields()

                if (response.status == false) {
                    $.each(response.message, function(key, item) {
                        if (key) {
                            $('.err_' + key).text(item)
                            $('#' + key).addClass('is-invalid');
                        }
                    });
                } else {

                    $('#table_amount').val(response.data.amount)

                    final_amount_calculation()
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

    function final_amount_calculation() {
        var table_amount = $('#table_amount').val();
        var discount_amount = $('#discount_amount').val();
        var service_charge = $('#service_charge').val();
        var extra_people_charge = $('#extra_people_charge').val();

        // Convert empty strings to 0 and then to float
        table_amount = table_amount === '' ? 0 : parseFloat(table_amount);
        discount_amount = discount_amount === '' ? 0 : parseFloat(discount_amount);
        service_charge = service_charge === '' ? 0 : parseFloat(service_charge);
        extra_people_charge = extra_people_charge === '' ? 0 : parseFloat(extra_people_charge);

        // Calculate total amount
        var total_amount = (table_amount + service_charge + extra_people_charge) - discount_amount;

        // Set the total amount value in the input field with two decimal places
        $('#total_amount').val(total_amount.toFixed(2));
    }

    $('#table_amount').keyup(function(e) {
        e.preventDefault();

        var key_amount = $(this).val();

        if (key_amount == "" || key_amount == 0) {
            var table_amount = parseFloat(0);

        } else {
            var table_amount = parseFloat(key_amount);
        }

        var discount_amount = $('#discount_amount').val();
        var service_charge = $('#service_charge').val();
        var extra_people_charge = $('#extra_people_charge').val();

        discount_amount = discount_amount === '' ? 0 : parseFloat(discount_amount);
        service_charge = service_charge === '' ? 0 : parseFloat(service_charge);
        extra_people_charge = extra_people_charge === '' ? 0 : parseFloat(extra_people_charge);

        if (discount_amount > table_amount) {
            $('#discount_amount').val(table_amount.toFixed(2))
            discount_amount = table_amount
        }

        var total_amount = parseFloat((table_amount + service_charge + extra_people_charge) - discount_amount);

        $('#total_amount').val(total_amount.toFixed(2))

    });

    $('#discount_amount').keyup(function(e) {
        e.preventDefault();

        var key_amount = $(this).val();

        if (key_amount == "" || key_amount == 0) {
            var discount_amount = parseFloat(0);
        } else {
            var discount_amount = parseFloat(key_amount);
        }

        var table_amount = $('#table_amount').val();
        var service_charge = $('#service_charge').val();
        var extra_people_charge = $('#extra_people_charge').val();

        table_amount = table_amount === '' ? 0 : parseFloat(table_amount);
        service_charge = service_charge === '' ? 0 : parseFloat(service_charge);
        extra_people_charge = extra_people_charge === '' ? 0 : parseFloat(extra_people_charge);

        if (discount_amount > table_amount) {
            $('#discount_amount').val(table_amount.toFixed(2))
            discount_amount = table_amount
        }

        var discount_amount = discount_amount;
        var total_amount = parseFloat((table_amount + service_charge + extra_people_charge) - discount_amount);

        $('#total_amount').val(total_amount.toFixed(2))

    });

    $('#service_charge').keyup(function(e) {
        e.preventDefault();

        var key_amount = $(this).val();

        if (key_amount == "" || key_amount == 0) {
            var service_charge = parseFloat(0);
        } else {
            var service_charge = parseFloat(key_amount);
        }

        var table_amount = $('#table_amount').val();
        var discount_amount = $('#discount_amount').val();
        var extra_people_charge = $('#extra_people_charge').val();

        table_amount = table_amount === '' ? 0 : parseFloat(table_amount);
        discount_amount = discount_amount === '' ? 0 : parseFloat(discount_amount);
        extra_people_charge = extra_people_charge === '' ? 0 : parseFloat(extra_people_charge);

        var service_charge = parseFloat(service_charge);
        var total_amount = parseFloat((table_amount + service_charge + extra_people_charge) - discount_amount);

        $('#total_amount').val(total_amount.toFixed(2))

    });

    $('#extra_people_charge').keyup(function(e) {
        e.preventDefault();

        var key_amount = $(this).val();

        if (key_amount == "" || key_amount == 0) {
            var extra_people_charge = parseFloat(0);
        } else {
            var extra_people_charge = parseFloat(key_amount);
        }

        var table_amount = $('#table_amount').val();
        var service_charge = $('#service_charge').val();
        var discount_amount = $('#discount_amount').val();

        table_amount = table_amount === '' ? 0 : parseFloat(table_amount);
        service_charge = service_charge === '' ? 0 : parseFloat(service_charge);
        discount_amount = discount_amount === '' ? 0 : parseFloat(discount_amount);

        var extra_people_charge = parseFloat(extra_people_charge);
        var total_amount = parseFloat((table_amount + service_charge + extra_people_charge) - discount_amount);

        $('#total_amount').val(total_amount.toFixed(2))

    });

    function clearAmountFields() {
        $('#table_amount').val('')
        $('#service_charge').val('')
        $('#discount_amount').val('')
        $('#total_amount').val('')
    }
</script>

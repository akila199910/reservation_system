<form id="createReservationForm" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="col-sm-12">
        <h4 class="mt-3 mb-4">Create Reservation</h4>
        <div class="row">
            <div class="col-sm-12">
                <div class="input-block local-forms">
                    <label>Select Client <span class="login-danger">*</span></label>
                    <select name="client" class="form-control client input-height-40" id="client">
                        <option value="">--Select Client--</option>
                        @foreach ($clients as $item)
                            <option value="{{ $item->id }}">{{ $item->name . ' - ' . $item->contact }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-danger font-weight-bold err_client"></small>
                </div>
            </div>

            <input type="hidden" name="location" id="location" value="{{ $tables->location_id }}">

            <div class="col-sm-12">
                <div class="input-block local-forms">
                    <label>Table</label>
                    <input class="form-control table input-height-40" value="{{ $tables->name }}" readonly
                        type="text" name="table_name" id="table_name" placeholder="">
                    <input class="form-control table input-height-40" value="{{ $tables->ref_no }}" readonly
                        type="hidden" name="table" id="table" placeholder="">
                    <small class="text-danger err_table"></small>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="input-block local-forms">
                    <label>Requested Date</label>
                    <input class="form-control input-height-40 requested_date" value="{{ $requested_date }}" readonly
                        type="text" name="requested_date" id="requested_date" placeholder="">
                    <small class="text-danger err_requested_date"></small>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="input-block local-forms">
                    <label>Start Time</label>
                    <input class="form-control start_time time_picker_field input-height-40" value="{{ $start_time }}"
                        type="text" name="start_time" id="start_time" placeholder="">
                    <small class="text-danger err_start_time"></small>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="input-block local-forms">
                    <label>End Time</label>
                    <input class="form-control time_picker_field input-height-40" value="{{ $end_time }}"
                        type="text" name="end_time" id="end_time" placeholder="">
                    <small class="text-danger err_end_time"></small>
                </div>
            </div>

            <div class="col-sm-12">
                <small class="text-danger err_duration"></small>
            </div>

            <div class="col-sm-6">
                <div class="input-block local-forms">
                    <label class="form-label" for="other-deducation">No of People</label>
                    <select name="no_of_people" class="form-control no_of_people input-height-40" id="no_of_people">
                        <option value=""></option>
                        {{-- @for ($i = 1; $i <= $tables->capacity; $i++) --}}
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <small class="text-danger font-weight-bold err_no_of_people"></small>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="input-block local-forms">
                    <label>No of Extra People</label>
                    <input type="number" name="no_of_extra_people" id="no_of_extra_people"
                        class="no_of_extra_people form-control number_only_val input-height-40" value="0">
                    <small class="text-danger font-weight-bold err_no_of_extra_people"></small>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="input-block local-forms">
                    <label>Reservation Note</label>
                    <textarea name="reservation_note" id="reservation_note" rows="5" class="form-control reservation_note"></textarea>
                    <small class="text-danger font-weight-bold err_reservation_note"></small>
                </div>
            </div>

            <div class="col-12 col-md-8 col-xl-5"></div>
            <div class="col-12 col-md-4 col-xl-7">
                <div class="row">

                    <div class="col-12 col-md-12 col-xl-12">
                        <div class="input-block local-forms">
                            <label>Table Amount <span class="login-danger">*</span></label>
                            <input type="text" name="table_amount"
                                value="{{ number_format($tables->amount, 2, '.', '') }}"
                                class="form-control table_amount decimal_val input-height-40" id="table_amount">
                            <small class="text-danger font-weight-bold err_table_amount"></small>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-xl-12">
                        <div class="input-block local-forms">
                            <label>Discount Amount</label>
                            <input type="text" name="discount_amount"
                                class="form-control discount_amount decimal_val input-height-40" id="discount_amount"
                                min="0">
                            <small class="text-danger font-weight-bold err_discount_amount"></small>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-xl-12">
                        <div class="input-block local-forms">
                            <label>Service Charge </label>
                            <input type="text" name="service_charge"
                                class="form-control service_charge decimal_val input-height-40" id="service_charge">
                            <small class="text-danger font-weight-bold err_service_charge"></small>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-xl-12">
                        <div class="input-block local-forms">
                            <label>Extra People Charge </label>
                            <input type="text" name="extra_people_charge"
                                class="form-control extra_people_charge decimal_val input-height-40"
                                id="extra_people_charge">
                            <small class="text-danger font-weight-bold err_extra_people_charge"></small>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-xl-12">
                        <div class="input-block local-forms">
                            <label>Total Amount<span class="login-danger">*</span></label>
                            <input type="text" readonly name="total_amount"
                                class="form-control total_amount decimal_val input-height-40" id="total_amount">
                            <small class="text-danger font-weight-bold err_total_amount"></small>
                        </div>
                    </div>

                    @if (Auth::user()->hasPermissionTo('Create_Reservation'))
                        <div class="col-12">
                            <div class="doctor-submit text-end">
                                <button type="submit"
                                    class="btn btn-primary text-uppercase submit-form me-2">Save</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</form>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            $('.time_picker_field').datetimepicker({
                format: 'LT',
                stepping: 30,
                icons: {
                    up: "fas fa-angle-up",
                    down: "fas fa-angle-down",
                    next: 'fas fa-angle-right',
                    previous: 'fas fa-angle-left'
                }
            });
        });

        final_amount_calculation()

        $('input[type="number"]').on('input', function() {
            // Remove any 'e', '-', or '+' characters
            this.value = this.value.replace(/[^0-9.]/g, '');

            // Ensure only one decimal point is allowed
            if ((self.val().match(/\./g) || []).length > 1) {
                self.val(self.val().slice(0, -1));
            }
        });
    });

    $(document).on("input", ".decimal_val", function() {
        var self = $(this);

        // Allow only numbers and a single decimal point
        self.val(self.val().replace(/[^0-9\.]/g, ''));

        // Prevent entering more than one decimal point
        if ((self.val().match(/\./g) || []).length > 1) {
            self.val(self.val().slice(0, -1)); // Remove the last entered decimal point
        }
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
        $('#table_amount').val('0.00')
        $('#service_charge').val('0.00')
        $('#discount_amount').val('0.00')
        $('#total_amount').val('0.00')
    }

    $('#createReservationForm').submit(function(e) {
        e.preventDefault();
        let formData = new FormData($('#createReservationForm')[0]);

        $.ajax({
            type: "POST",
            beforeSend: function() {
                $('#loader').show()
            },
            url: "{{ route('business.reservation.create') }}",
            data: formData,
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $('#loader').hide()

                clearError();

                if (response.status == false) {
                    $.each(response.message, function(key, item) {
                        if (key) {
                            $('.err_' + key).text(item)
                        }
                    });
                } else {
                    successPopup(response.message, '')
                    //refresh the calendar event and resources
                    calendar.refetchEvents();
                    calendar.refetchResources();
                    $('#offcanvasRight').offcanvas('hide');
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

    function clearError() {
        $('.err_client').text('');

        $('.err_requested_date').text('');

        $('.err_start_time').text('');

        $('.err_end_time').text('');

        $('.err_duration').text('')

        $('.err_table').text('');

        $('.err_no_of_people').text('');

        $('.err_table_amount').text('');

        $('.err_discount_amount').text('');

        $('.err_service_charge').text('');

        $('.err_total_amount').text('');

        $('.err_location').text('');
    }
</script>

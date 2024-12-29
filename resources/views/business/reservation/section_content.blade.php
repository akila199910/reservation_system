<h4>Select Section</h4>
<hr>
<div class="radio-group">

    @foreach ($preference as $key => $item)
        <div class="custom-radio-section">
            <input type="radio" class="preference" id="preference_{{ $item->id }}" name="preference"
                value="{{ $item->id }}">
            <label for="preference_{{ $item->id }}">
                <img src="{{ config('aws_url.url') . $item->image }}" alt="preference {{ $item->id }}">
                <span>{{ ucwords($item->preference) }}</span>
            </label>
        </div>
    @endforeach

</div>

<script>
    $('input[name="preference"]').change(function() {
        // Get the value of the selected radio button
        var preference_id = $(this).val();

        if (preference_id != '') {
            var data = {
                'client': $('#client').val(),
                'location': $('#location').val(),
                'requested_date': $('#requested_date').val(),
                'start_time': $('#start_time').val(),
                'end_time': $('#end_time').val(),
                'preference_id': preference_id
            }


            $.ajax({
                type: "POST",
                beforeSend: function() {
                    $('#loader').show()
                },
                url: "{{ route('business.reservation.get_available_table') }}",
                data: data,
                success: function(response) {
                    $('#loader').hide()

                    clearError();

                    $('.available_table_data').html('')
                    $('.save_button_div').hide()

                    if (response.status == false) {
                        $.each(response.message, function(key, item) {
                            if (key) {
                                $('.err_' + key).text(item)
                            }
                        });
                        $('input[name="preference"]:checked').prop('checked', false);
                    } else {

                        $('.available_table_data').html(response)
                        $('.save_button_div').show()
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
        }

    });
</script>

<form id="submitUpdateForm" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-heading">
                <h4>Update Table Type</h4>
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="{{ $table_type->id }}">
        <div class="col-12 col-md-6 col-xl-6">
            <div class="input-block local-forms">
                <label for="table_type">Table Type<span class="text-danger">*</span></label>
                <input type="text" name="table_type" class="form-control table_type"
                    value="{{ $table_type->type_name }}" id="table_type" maxlength="190">
                <small class="text-danger font-weight-bold err_table_type"></small>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-6">
            <div class="doctor-submit text-end">
                <button type="submit" class="btn btn-primary text-uppercase submit-form me-2">
                    Update
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $('#submitUpdateForm').submit(function(e) {
        e.preventDefault();

        let formData = new FormData($('#submitUpdateForm')[0]);

        $.ajax({
            type: "POST",
            beforeSend: function() {
                $("#loader").show();
            },
            url: "{{ route('admin.table_types.update') }}",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $("#loader").hide();
                errorClear()
                if (response.status == false) {
                    $.each(response.message, function(key, item) {
                        if (key) {
                            $('.err_' + key).text(item)
                            $('#' + key).addClass('is-invalid');
                        }
                    });
                } else {
                    successPopup('Selected Table Type Updated Successfully!', '')
                    $('#submitUpdateForm')[0].reset();
                    $('._create_update_div').html('')
                    $('._create_update_div').html(response)
                    table.clear();
                    table.ajax.reload();
                    table.draw();
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

<form id="submitCreateForm" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-heading">
                <h4>Create New Table Type</h4>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-6">
            <div class="input-block local-forms">
                <label for="table_type">Table Type<span class="text-danger">*</span></label>
                <input type="text" name="table_type" class="form-control table_type" id="table_type" maxlength="190">
                <small class="text-danger font-weight-bold err_table_type"></small>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-6">
            <div class="doctor-submit text-end">
                <button type="submit" class="btn btn-primary text-uppercase submit-form me-2">
                    Create
                </button>
             </div>
        </div>
    </div>
</form>

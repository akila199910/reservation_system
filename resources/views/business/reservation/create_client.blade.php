 <!-- Edit Payroll model -->
 <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
     <div class="offcanvas-header">
         {{-- <h4 id="offcanvasRightLabel" class="text-uppercase">Reservation Details</h4> --}}
         <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
     </div>
     <div class="offcanvas-body _reservation_data_body">
         <!-- Dynamic body will show here -->
         <form id="createClientForm" method="POST" enctype="multipart/form-data">
             @csrf

             <div class="col-sm-12">
                 <h4 class="mt-3 mb-4">Create Client</h4>
                 <div class="row">
                     <div class="col-12 col-md-12 col-xl-12">
                         <div class="input-block local-forms">
                             <label for="first_name">First Name <span class="text-danger">*</span> </label>
                             <input type="text" name="first_name" class="form-control first_name" id="first_name"
                                 maxlength="190">
                             <small class="text-danger font-weight-bold err_first_name"></small>
                         </div>
                     </div>

                     <div class="col-12 col-md-12 col-xl-12">
                         <div class="input-block local-forms">
                             <label for="last_name">Last Name <span class="text-danger">*</span></label>
                             <input type="text" name="last_name" class="form-control last_name" id="last_name"
                                 maxlength="190">
                             <small class="text-danger font-weight-bold err_last_name"></small>
                         </div>
                     </div>

                     <div class="col-12 col-md-12 col-xl-12">
                         <div class="input-block local-forms">
                             <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                             <input type="email" name="email" class="form-control email" id="email"
                                 maxlength="190">
                             <small class="text-danger font-weight-bold err_email"></small>
                         </div>
                     </div>

                     <div class="col-12 col-md-12 col-xl-12">
                         <div class="input-block local-forms">
                             <label for="contact">Contact <span class="text-danger">*</span></label>
                             <input type="text" name="contact" class="form-control contact number_only_val"
                                 maxlength="10" id="contact" maxlength="190">
                             <small class="text-danger font-weight-bold err_contact"></small>
                         </div>
                     </div>

                     <input type="hidden" name="status" value="1">

                     @if (Auth::user()->hasPermissionTo('Create_Client'))
                         <div class="col-12">
                             <div class="doctor-submit text-end">
                                 <button type="submit"
                                     class="btn btn-primary text-uppercase submit-form me-2">Save</button>
                             </div>
                         </div>
                     @endif
                 </div>
             </div>
         </form>
     </div>
 </div>


 <!-- END ------------------------------>

 <script>
     $('#createClientForm').submit(function(e) {
         e.preventDefault();
         let formData = new FormData($('#createClientForm')[0]);

         $.ajax({
             type: "POST",
             beforeSend: function() {
                 $("#loader").show();
             },
             url: "{{ route('business.clients.create') }}",
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
                    console.log(response);

                    $('.client').append('<option value="'+response.id+'">'+response.name+' - '+response.contact+'</option>');

                    successPopup(response.message, '')

                    $('.client').change().val(response.id)

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

     function errorClear() {
         $('#first_name').removeClass('is-invalid')
         $('.err_first_name').text('')

         $('#last_name').removeClass('is-invalid')
         $('.err_last_name').text('')

         $('#email').removeClass('is-invalid')
         $('.err_email').text('')

         $('#contact').removeClass('is-invalid')
         $('.err_contact').text('')
     }
 </script>

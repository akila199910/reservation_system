{{-- <div class="col-md-8 col-xl-8 col-sm-12"></div>
<div class="col-12 col-md-4 col-xl-4">
    <input type="text" name="search_elements_name" class="form-control search_elements_name" id="search_elements_name"
        placeholder="Enter Element Name ..." maxlength="190">
</div> --}}
<!-- Set Element type id -->
<input type="hidden" name="element_type_id" id="element_type_id" value="{{ $element_type_id }}">
<!-- END -->

<div class="col-12 mt-4 ">
    <div class="radio-group main_element_list">

        @if (count($elements))
            @foreach ($elements as $item)
                <div class="custom-radio-section-table">
                    <input type="radio" class="element" id="element_{{ $item->id }}"
                        {{ $item->id == $find_cafe->element_id ? 'checked' : '' }} name="element"
                        value="{{ $item->id }}">
                    <label for="element_{{ $item->id }}">
                        <img src="{{ $item->normal_image }}" title="{{ $item->layout_name }}"
                            alt="element {{ $item->id }}">
                    </label>
                </div>
            @endforeach
        @else
            <div class="col-lg-12 col-md-12 col-sm-12 mb-2 text-center">
                <span class="text-danger p-5">Sorry No Data Found!</span>
            </div>
        @endif
        <small class="text-danger err_element"></small>
    </div>

    <div class="row _element_search_list_div">


    </div>
</div>


<script>
    var selected_element_id = '';
    $(document).ready(function() {

        $('#search_elements_name').on('keyup', function() {
            let search_value = $(this).val().trim(); // Trim the input to avoid leading/trailing spaces

            if (search_value !== '' && search_value.length >= 3) {
                var data = {
                    'element_type': $('#element_type_id').val(),
                    'element_name': search_value
                };

                $.ajax({
                    type: "GET",
                    url: "{{ route('business.cafe.filter_elements') }}",
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        var html = '';
                        if (response.status === false) {
                            html += `
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-2 text-center">
                            <span class="text-danger p-5">Sorry, No Data Found!</span>
                        </div>`;
                        } else {
                            $.map(response.data, function(item) {
                                html += `
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                                <div class="custom-radio-section-table">
                                    <input type="radio" class="element" id="element_search_${item.id}" name="element" value="${item.id}">
                                    <label for="element_search_${item.id}">
                                        <img src="${item.normal_image}" alt="element ${item.id}">
                                        <span style="font-size: 12px">${item.layout_name}</span>
                                    </label>
                                </div>
                            </div>`;
                            });
                        }

                        $('._element_search_list_div').html(html);
                        $('.main_element_list').hide();
                        $('._element_search_list_div').show();

                    },
                    statusCode: {
                        401: function() {
                            window.location.href = '{{ route('login') }}';
                        },
                        419: function() {
                            window.location.href = '{{ route('login') }}';
                        },
                    },
                    error: function(data) {
                        console.error('Error fetching elements:', data);
                        someThingWrong();
                    }
                });
            } else {
                $('.main_element_list').show();
                $('._element_search_list_div').hide();
            }
        });
    });
</script>

<div class="col-md-6 col-xl-6 col-sm-12"></div>
<div class="col-12 col-md-6 col-xl-6">
    <input type="text" name="search_elements_name" class="form-control search_elements_name" id="search_elements_name"
        placeholder="Enter Element Name ..." maxlength="190">
</div>

<div class="col-12 mt-4">
    <div class="row _element_list_div">
        <input type="hidden" name="element_type_id" id="element_type_id" value="{{ $first_element_type->id }}">
        @if (count($elements))
            @foreach ($elements as $item)
                <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                    <div class="custom-radio-section-table">
                        <input type="radio" class="element" id="element_{{ $item->id }}" name="element"
                            value="{{ $item->id }}">
                        <label for="element_{{ $item->id }}">
                            <img src="{{ config('aws_url.url') . $item->normal_image }}"
                                alt="element {{ $item->id }}">
                            <span style="font-size: 12px">{{ ucwords($item->layout_name) }}</span>
                        </label>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-lg-12 col-md-12 col-sm-12 mb-2 text-center">
                <span class="text-danger p-5">Sorry No Data Found!</span>
            </div>
        @endif
    </div>
</div>

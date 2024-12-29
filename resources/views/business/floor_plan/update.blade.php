@extends('layouts.business')

@section('title')
    Manage Floor Plans
@endsection

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.cafe') }}">Manage Floor Plans</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Update Floor Plan</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.floor_plan') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <!-- Heading Section -->
                    <div class="form-heading">
                        <h4>Update Floor Plan</h4>
                    </div>

                    <!-- Selected Section Info -->
                    <div class="form-group">
                        <label style="font-size: 16px; font-weight:600">Selected Section</label>
                        <input type="hidden" name="section_id" id="section" value="{{ $floor_plan->section_id }}">
                        <p>{{ ucwords($floor_plan->preference_info->preference . ' (Location - ' . $floor_plan->preference_info->location->location_name . ')') }}</p>
                    </div>

                    <!-- Section Table List and Floor Plan Container -->
                    <div class="col-12 col-md-12 col-xl-12 _section_table_list">
                        <div class="row">
                            <!-- Left Side: Available Tables -->
                            <div class="col-lg-3 left" id="left">
                                <div class="row">
                                    @foreach ($tables as $item)
                                        <div class="col-lg-12 mb-2">
                                            <div class="table-number drag_element">
                                                <label for="element_{{ $item->id }}">
                                                    <img src="{{ $item->element_info->normal_image }}" data-id="{{ $item->id }}" alt="element {{ $item->id }}" class="shape-img" id="element_{{ $item->id }}">
                                                    <span class="text-container">
                                                        <span class="element_name">{{ $item->name }}</span>
                                                        <span class="layout_namee">{{ '(' . $item->element_info->layout_name . ')' }}</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Right Side: Floor Plan Container -->
                            <div class="col-lg-9 col-md-9 col-sm-12 floor_container">
                                <div class="input-block local-forms">
                                    <small class="text-danger font-weight-bold err_dropped_shape_data"></small>
                                    <div id="floorPlanContainer" class="drop_element" style="position: relative; border: 1px solid #ccc;">
                                        @foreach ($floor_plan->tables as $item)
                                        <div class="ui-wrapper" style="position: absolute; left: {{ $item->table_pos_x }}px; top: {{ $item->table_pos_y }}px; width: {{ $item->table_width }}px; height: {{ $item->table_height }}px;">
                                            <img src="{{ $item->table_info->element_info->normal_image }}" alt="Table {{ $item->id }}" style="width: 100%; height: 100%;">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Setup Button (Only if User Has Permission) -->
                    @if (Auth::user()->hasPermissionTo('Update_Floor_Plan') && count($tables))
                        <div class="col-12 mt-4 text-end">
                            <button type="button" onclick="setupFloorPlan()" class="btn btn-primary text-uppercase submit-form">Setup</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function ($) {
            let droppedShapeData = {};

            $(document).ready(function () {
                initializeElements();

                // Initialize existing draggable and resizable elements
                function initializeElements() {
                    $(".ui-wrapper").draggable({
                        containment: "#floorPlanContainer",
                        scroll: false,
                        stop: updatePositionAndSize
                    }).resizable({
                        containment: "#floorPlanContainer",
                        handles: "n, e, s, w, ne, se, sw, nw",
                        minWidth: 40,
                        minHeight: 40,
                        resize: resizeImage,
                        stop: updateSize
                    }).each(function () {
                        addDeleteButton($(this));
                    });

                    $(".shape-img").draggable({
                        helper: "clone",
                        revert: "invalid",
                        zIndex: 1000
                    });

                    $("#floorPlanContainer").droppable({
                        accept: ".shape-img",
                        drop: dropNewShape
                    });
                }

                $(".ui-wrapper").resizable({
                    containment: "#floorPlanContainer",
                    aspectRatio: true,  // Ensures the element maintains its aspect ratio
                    maxWidth: $("#floorPlanContainer").width(),
                    maxHeight: $("#floorPlanContainer").height()
                });
                // Update position and size in droppedShapeData
                function updatePositionAndSize(event, ui) {
                    const elementId = $(this).find("img").data("id");

                    // Ensure the elementId exists in droppedShapeData before updating its properties
                    if (droppedShapeData[elementId]) {
                        // Only update if elementId is found in droppedShapeData
                        droppedShapeData[elementId].left = ui.position.left;
                        droppedShapeData[elementId].top = ui.position.top;
                        droppedShapeData[elementId].width = ui.size.width;
                        droppedShapeData[elementId].height = ui.size.height;
                    } else {
                        console.error(`Element with ID ${elementId} not found in droppedShapeData.`);
                    }
                }

                // Resize image inside resizable
                function resizeImage(event, ui) {
                    $(this).find("img").css({
                        width: ui.size.width + "px",
                        height: ui.size.height + "px"
                    });
                }

                // Update size after resizing
                function updateSize(event, ui) {
                    const elementId = $(this).find("img").data("id");
                    updateShapeData(elementId, null, null, ui.size.width, ui.size.height);
                }

                // Drop new shape and initialize draggable and resizable
                function dropNewShape(event, ui) {
                    const imageSrc = ui.helper.attr("src");
                    const imageId = ui.helper.attr("data-id");

                    if (droppedShapeData[imageId]) {
                        console.log("Shape already dropped.");
                        return;
                    }

                    const wrapper = createWrapper(imageSrc, imageId, event);
                    $("#floorPlanContainer").append(wrapper);

                    droppedShapeData[imageId] = {
                        left: parseInt(wrapper.css("left")),
                        top: parseInt(wrapper.css("top")),
                        width: 80,
                        height: 80
                    };

                    wrapper.draggable({ containment: "#floorPlanContainer", stop: updatePositionAndSize })
                           .resizable({ containment: "#floorPlanContainer", stop: updateSize });
                    addDeleteButton(wrapper);
                }

                // Create a new wrapper for dropped shape
                function createWrapper(imageSrc, imageId, event) {
                    const wrapper = $('<div class="ui-wrapper"></div>').css({
                        position: "absolute",
                        left: event.pageX - $("#floorPlanContainer").offset().left - 40 + "px",
                        top: event.pageY - $("#floorPlanContainer").offset().top - 40 + "px",
                        width: "80px",
                        height: "80px"
                    });

                    const newShape = $('<img class="dropped-shape" />').attr({
                        src: imageSrc,
                        "data-id": imageId
                    }).css({ width: "100%", height: "100%" });

                    wrapper.append(newShape);
                    return wrapper;
                }

                // Add delete button to shape wrapper
                function addDeleteButton(wrapper) {
                    $('<button class="delete-btn">X</button>').css({
                        position: "absolute",
                        top: "-10px",
                        right: "-10px",
                        width: "20px",
                        height: "20px",
                        background: "red",
                        color: "white",
                        border: "none",
                        borderRadius: "50%",
                        cursor: "pointer",
                        zIndex: 2000
                    }).on("click", function () {
                        const elementId = wrapper.find("img").data("id");
                        delete droppedShapeData[elementId];
                        wrapper.remove();
                    }).appendTo(wrapper);
                }

                // Submit floor plan setup
                window.setupFloorPlan = function () {
                    let formData = [];
                    for (let id in droppedShapeData) {
                        formData.push({
                            table_id: id,
                            table_pos_x: droppedShapeData[id].left,
                            table_pos_y: droppedShapeData[id].top,
                            table_width: droppedShapeData[id].width,
                            table_height: droppedShapeData[id].height
                        });
                    }

                    if (formData.length) {
                        $(".err_dropped_shape_data").hide();
                        $.ajax({
                            url: "{{ route('business.floor_plan.create', $floor_plan->id) }}",
                            method: "POST",
                            data: {
                                tables: formData,
                                _token: "{{ csrf_token() }}",
                                section_id: $('#section').val()
                            },
                            success: function (response) {
                                if (response.success) {
                                    window.location.reload();
                                } else {
                                    $(".err_dropped_shape_data").text(response.message).show();
                                }
                            }
                        });
                    } else {
                        $(".err_dropped_shape_data").text("No shapes have been added.").show();
                    }
                };
            });
        })(jQuery);
    </script>
@endsection

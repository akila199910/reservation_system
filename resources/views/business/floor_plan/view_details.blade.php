@extends('layouts.business')

@section('title')
    Manage Floor Plans
@endsection

<style>
    /* Disable blue outline around draggable elements */
    .ui-draggable-dragging,
    .ui-resizable-resizing {
        outline: none !important;
        box-shadow: none !important;
    }

    /* Ensure the resizable handles don't have any additional styles */
    .ui-resizable-handle {
        outline: none !important;
        box-shadow: none !important;
    }

    /* General style for dropped shapes */
    .dropped-shape {
        border: none !important;
        /* Removes any border */
    }

    .ui-wrapper {
        border: none !important;
    }
</style>


@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-sm-8">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('business.floor_plan') }}">Manage Floor Plans</a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Floor Plan Details</li>
                </ul>
            </div>
            <div class="col-sm-4 text-end">
                <a href="{{ route('business.floor_plan') }}" class="btn btn-primary" style="width: 100px">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="doctor-table-blk mb-4 pt-2">
                                            <h3 class="text-uppercase">Floor Plan Details</h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Section Name</h2>
                                                    <h3>{{ ucwords($floor_plan->preference_info->preference . ' (Location - ' . $floor_plan->preference_info->location->location_name . ')') }}
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 mb-3">
                                                <div class="detail-personal">
                                                    <h2>Status</h2>
                                                    <h3>
                                                        @php
                                                            $status = $floor_plan->status == 1 ? 'Active' : 'Inactive';
                                                            $badgeClass =
                                                                $floor_plan->status == 1
                                                                    ? 'custom-badge status-green'
                                                                    : 'custom-badge status-red';
                                                        @endphp
                                                        <span class="{{ $badgeClass }}">{{ $status }}</span>
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="col-lg-9 col-md-9 col-sm-12 floor_container">
                                                <div class="input-block local-forms">
                                                    <small
                                                        class="text-danger font-weight-bold err_dropped_shape_data"></small>
                                                    <div class="drop_element" id="floorPlanContainer"
                                                        style="position: relative; border: 1px solid #ccc;">
                                                        @foreach ($floor_plan->tables as $item)
                                                            <div class="ui-wrapper" style="position: absolute; left: {{ $item->table_pos_x }}px; top: {{ $item->table_pos_y }}px; width: {{ $item->table_width }}px; height: {{ $item->table_height }}px;">
                                                                <img src="{{ $item->table_info->element_info->normal_image }}" data-id="{{ $item->table_id }}" alt="element {{ $item->table_id }}" class="dropped-shape" style="width: {{ $item->table_width }}px; height: {{ $item->table_height }}px;">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var dropped_shape_data = {}; // Use an object for better key-value storage
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        })

        $(document).ready(function() {
            // Ensure jQuery UI draggable is available
            if (typeof $.fn.draggable === "undefined" || typeof $.fn.droppable === "undefined") {
                console.error("jQuery UI is not loaded properly.");
                return;
            }

            var droppedShapes = [];

            // Make images draggable
            $(".shape-img").draggable({
                helper: "clone", // Clone the image when dragged
                revert: "invalid", // Revert if dropped outside a valid container
                zIndex: 1000, // Ensure the dragged item is above all other elements
            });

            // Make floorPlanContainer a droppable area
            $("#floorPlanContainer").droppable({
                accept: ".shape-img", // Only accept .shape-img elements
                drop: function(event, ui) {
                    // Get the source and ID of the image being dropped
                    const imageSrc = ui.helper.attr("src");
                    const imageId = ui.helper.attr("data-id");

                    // Check if the shape has already been dropped
                    if (droppedShapes.includes(imageId)) {
                        console.log("This shape has already been dropped.");
                        return; // Prevent duplicate drop
                    }

                    // Mark the shape as dropped
                    droppedShapes.push(imageId);

                    // Initialize shape data in dropped_shape_data
                    dropped_shape_data[imageId] = {
                        src: imageSrc,
                        left: 0,
                        top: 0,
                        width: 80, // Default width
                        height: 80, // Default height
                    };

                    // Get mouse position within the container
                    const mouseX = event.pageX - $(this).offset().left;
                    const mouseY = event.pageY - $(this).offset().top;

                    // Clone the dragged image
                    const newShape = ui.helper.clone();
                    newShape.removeClass("shape-img").addClass("dropped-shape");

                    // Create a wrapper for resizing
                    const wrapper = $('<div class="ui-wrapper"></div>').append(newShape);

                    // Style and position the wrapper
                    wrapper.css({
                        position: "absolute",
                        left: Math.max(0, Math.min(mouseX - 40, $(this).width() - 80)) + "px",
                        top: Math.max(0, Math.min(mouseY - 40, $(this).height() - 80)) + "px",
                        width: "80px",
                        height: "80px",
                    });

                    // Append to the container
                    $(this).append(wrapper);

                    // Update dropped shape data with initial position and dimensions
                    dropped_shape_data[imageId].left = parseInt(wrapper.css("left"));
                    dropped_shape_data[imageId].top = parseInt(wrapper.css("top"));

                    console.log("Dropped Shape Data:", dropped_shape_data);

                    // Make the wrapper draggable
                    wrapper.draggable({
                        containment: "#floorPlanContainer", // Restrict within container
                        scroll: false, // Prevent scrolling during drag
                        stop: function(event, ui) {
                            // Update shape data on drag stop
                            dropped_shape_data[imageId].left = ui.position.left;
                            dropped_shape_data[imageId].top = ui.position.top;

                            console.log("Updated Shape Data on Drag Stop:",
                                dropped_shape_data[imageId]);
                        },
                    });

                    // Make the shape resizable within the wrapper
                    newShape.resizable({
                        containment: "#floorPlanContainer", // Restrict resizing within container
                        handles: "n, e, s, w, ne, se, sw, nw", // Resize from all sides and corners
                        minWidth: 40, // Minimum width
                        minHeight: 40, // Minimum height
                        alsoResize: wrapper, // Resize the wrapper along with the image
                        stop: function() {
                            // Update shape data on resize stop
                            dropped_shape_data[imageId].width = wrapper.width();
                            dropped_shape_data[imageId].height = wrapper.height();

                            console.log("Updated Shape Data on Resize Stop:",
                                dropped_shape_data[imageId]);
                        },
                    });
                },
            });
        });
    </script>
@endsection

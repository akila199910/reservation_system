<div class="row">
    @if (count($tables))
        <div class="col-lg-3 col-md-3 col-sm-12 left" id="left">
            <div class="row">
                @foreach ($tables as $item)
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2" id="table_{{ $item->id }}">
                        <div class="table-number drag_element">
                            <label for="element_{{ $item->id }}">
                                <img src="{{ $item->element_info->normal_image }}" data-id="{{ $item->id }}"
                                    alt="element {{ $item->id }}" class="shape-img"
                                    id="element_{{ $item->id }}">
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

        <div class="col-lg-9 col-md-9 col-sm-12 floor_container">
            <div class="input-block local-forms">
                <small class="text-danger font-weight-bold err_dropped_shape_data"></small>
                <div class="drop_element" id="floorPlanContainer" style="position: relative; border: 1px solid #ccc;">

                </div>
            </div>
        </div>

        @if (Auth::user()->hasPermissionTo('Create_Floor_Plan') && count($tables))
            <div class="col-12 mt-4">
                <div class="doctor-submit text-end">
                    <button type="button" onclick="setup_floor_plan()"
                        class="btn btn-primary text-uppercase submit-form me-2">Setup</button>
                </div>
            </div>
        @endif
    @else
        <div class="col-12 text-center">
            <span class="text-danger">No Element Found</span>
        </div>
    @endif
</div>



<script>
    var dropped_shape_data = {}; // Object to store data for dropped shapes
    var droppedShapes = []; // Array to track IDs of dropped shapes

    $(document).ready(function () {
        // Ensure jQuery UI is loaded
        if (typeof $.fn.draggable === "undefined" || typeof $.fn.droppable === "undefined") {
            console.error("jQuery UI is not loaded properly.");
            return;
        }

        // Make images draggable from the side panel
        $(".shape-img").draggable({
            helper: "clone", // Clone the element while dragging
            revert: "invalid", // Revert to original position if not dropped in valid container
            zIndex: 1000, // Ensure dragged item is above all other elements
        });

        // Make the #floorPlanContainer droppable
        $("#floorPlanContainer").droppable({
            accept: ".shape-img", // Only accept draggable elements with the class .shape-img
            drop: function (event, ui) {
                const imageSrc = ui.helper.attr("src");
                const imageId = ui.helper.attr("data-id");

                // Prevent duplicate drops
                if (droppedShapes.includes(imageId)) {
                    console.log("This shape has already been dropped.");
                    return;
                }

                // Add the shape ID to the array
                droppedShapes.push(imageId);

                // Initialize shape data
                dropped_shape_data[imageId] = {
                    src: imageSrc,
                    left: 0,
                    top: 0,
                    width: 80, // Default width
                    height: 80, // Default height
                };

                // Calculate drop position within the container
                const containerOffset = $(this).offset(); // Offset of the container
                const mouseX = event.pageX - containerOffset.left;
                const mouseY = event.pageY - containerOffset.top;

                // Create a wrapper for the dropped shape
                const wrapper = $('<div class="ui-wrapper"></div>').css({
                    position: "absolute",
                    left: Math.max(0, Math.min(mouseX - 40, $(this).width() - 80)) + "px",
                    top: Math.max(0, Math.min(mouseY - 40, $(this).height() - 80)) + "px",
                    width: "80px",
                    height: "80px",
                    border: "1px solid blue",
                });

                // Create the new shape (image)
                const newShape = $('<img class="dropped-shape" />').attr({
                    src: imageSrc,
                    "data-id": imageId,
                    style: "width: 100%; height: 100%;",
                });

                // Append the new shape and delete button to the wrapper
                wrapper.append(newShape);
                wrapper.append(createDeleteButton(wrapper, imageId));

                // Add the wrapper to the container
                $(this).append(wrapper);

                // Hide the side bar tables
                $('#table_' + imageId).hide();

                // Update dropped_shape_data with initial position and dimensions
                dropped_shape_data[imageId].left = parseInt(wrapper.css("left"));
                dropped_shape_data[imageId].top = parseInt(wrapper.css("top"));

                console.log("Dropped Shape Data:", dropped_shape_data);

                // Make the wrapper draggable
                wrapper.draggable({
                    containment: "#floorPlanContainer", // Restrict within container
                    scroll: false, // Prevent scrolling during drag
                    stop: function (event, ui) {
                        const containerOffset = $("#floorPlanContainer").offset();
                        const wrapperOffset = $(this).offset();

                        // Calculate position relative to the container
                        dropped_shape_data[imageId].left = wrapperOffset.left - containerOffset.left;
                        dropped_shape_data[imageId].top = wrapperOffset.top - containerOffset.top;

                        console.log("Updated Position Relative to Container:", dropped_shape_data[imageId]);
                    },
                });

                // Make the shape resizable
                wrapper.resizable({
                    containment: "#floorPlanContainer", // Restrict resizing within container
                    handles: "n, e, s, w, ne, se, sw, nw", // Allow resizing from all sides and corners
                    minWidth: 40,
                    minHeight: 40,
                    resize: function (event, ui) {
                        newShape.css({
                            width: ui.size.width + "px",
                            height: ui.size.height + "px",
                        });
                    },
                    stop: function (event, ui) {
                        dropped_shape_data[imageId].width = ui.size.width;
                        dropped_shape_data[imageId].height = ui.size.height;
                        console.log("Updated Size:", dropped_shape_data[imageId]);
                    },
                });
            },
        });
    });

    // Function to create a delete button
    function createDeleteButton(wrapper, imageId) {
        const deleteButton = $('<button class="delete-btn">X</button>').css({
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
            zIndex: 2000,
        });

        deleteButton.on("click", function () {
            // Remove the elementId from droppedShapes array
            const index = droppedShapes.indexOf(imageId);
            if (index > -1) {
                droppedShapes.splice(index, 1); // Remove the elementId from the array
            }

            // Remove the shape data from dropped_shape_data object
            delete dropped_shape_data[imageId];

            // Remove the element from the DOM
            wrapper.remove();

            console.log(`Deleted Shape ID: ${imageId}`);
            console.log("Updated droppedShapes:", droppedShapes);

            // Show the side bar tables
            $('#table_' + imageId).show();
        });

        return deleteButton;
    }

    // Function to save the layout
    function setup_floor_plan() {
        const data = {
            section_id: $("#section").val(),
            dropped_shape_data: dropped_shape_data,
        };

        $("#loader").show();

        $.ajax({
            type: "POST",
            url: "{{ route('business.floor_plan.create') }}",
            data: data,
            dataType: "JSON",
            success: function (response) {
                $("#loader").hide();
                if (!response.status) {
                    $.each(response.message, function (key, item) {
                        if (key) {
                            $(".err_" + key).text(item);
                            $("#" + key).addClass("is-invalid");
                        }
                    });
                } else {
                    successPopup(response.message, response.route);
                }
            },
            statusCode: {
                401: function () {
                    window.location.href = "{{ route('login') }}";
                },
                419: function () {
                    window.location.href = "{{ route('login') }}";
                },
            },
            error: function (data) {
                console.error("Error:", data);
                someThingWrong();
            },
        });
    }

    function errorClear() {
        $('#section').removeClass('is-invalid')
        $('.err_section').text('')
    }

    $(window).on("resize", function () {
    $(".dropped-shape").draggable("option", "containment", "#floorPlanContainer");
}).trigger("resize"); // Ensure this runs on initial load.

</script>

<script>
    $(document).ready(function () {
    function setDraggableAndReposition() {
        $(".dropped-shape").draggable({
            containment: "#floorPlanContainer",
            scroll: false,
        });

        $(window).on("resize", function () {
            $(".dropped-shape").each(function () {
                const $shape = $(this);
                const containerWidth = $("#floorPlanContainer").width();
                const containerHeight = $("#floorPlanContainer").height();

                // Ensure the shape remains visible within the new container dimensions
                const shapeOffset = $shape.offset();
                const shapeWidth = $shape.outerWidth();
                const shapeHeight = $shape.outerHeight();

                const newLeft = Math.min(shapeOffset.left, containerWidth - shapeWidth);
                const newTop = Math.min(shapeOffset.top, containerHeight - shapeHeight);

                // Adjust position if shape is outside the new bounds
                $shape.css({
                    left: newLeft < 0 ? 0 : newLeft,
                    top: newTop < 0 ? 0 : newTop,
                });
            });
        }).trigger("resize"); // Trigger resize on page load to ensure correct position
    }

    setDraggableAndReposition();
});


</script>


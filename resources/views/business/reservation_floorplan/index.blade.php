@extends('layouts.business')

@section('title')
    Manage Reservations
@endsection

<link type="text/css" href="{{ asset('calendarstyle/css/style.css?v=') . time() }}" rel="stylesheet">
<link type="text/css" href="{{ asset('calendarstyle/css/mobiscroll.jquery.min.css') }}" rel="stylesheet">

<style>
    .ui-wrapper{
        border: none !important;
    }
</style>

@section('content')
    <link type="text/css" href="{{ asset('calendarstyle/css/style_new.css') }}" rel="stylesheet">

    <div class="row calendar-main-row">
        <div class="calendar-sidemenu">
            <div class="cal-side-top dayview-cal-top">

                <h2>Reservation Calendar</h2>

                <div class="mobile-shortcut-btn">
                    <a href="javascript:;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12.609" height="10.508" viewBox="0 0 12.609 10.508">
                            <path id="format_list_bulleted_FILL0_wght400_GRAD0_opsz48"
                                d="M6.893,19.508a.875.875,0,0,1-.63-.254A.812.812,0,0,1,6,18.65a.9.9,0,0,1,.893-.893.812.812,0,0,1,.6.263.875.875,0,0,1,.254.63.867.867,0,0,1-.858.858Zm2.609-.35V18.107h9.107v1.051ZM6.893,15.13a.875.875,0,0,1-.63-.254.867.867,0,0,1,0-1.243.875.875,0,0,1,.63-.254.812.812,0,0,1,.6.263.867.867,0,0,1,0,1.226.812.812,0,0,1-.6.263Zm2.609-.35V13.729h9.107v1.051ZM6.876,10.751A.867.867,0,1,1,7.5,10.5.846.846,0,0,1,6.876,10.751ZM9.5,10.4V9.35h9.107V10.4Z"
                                transform="translate(-6 -9)" fill="#304aca" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="create-appt-btn">

            </div>

            <div class="mobi-calendar-cont">
                <div class="inline-picker" id="custom-inline-pick"></div>
            </div>
        </div>

        <div class="side-overlayclick"></div>

        <div class="tonic-calendar-cont">
            <div class="fc-rightcustom-cont">
                <div class="shorcut-toggle-btn">
                    <a href="javascript:;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12.609" height="10.508" viewBox="0 0 12.609 10.508">
                            <path id="format_list_bulleted_FILL0_wght400_GRAD0_opsz48"
                                d="M6.893,19.508a.875.875,0,0,1-.63-.254A.812.812,0,0,1,6,18.65a.9.9,0,0,1,.893-.893.812.812,0,0,1,.6.263.875.875,0,0,1,.254.63.867.867,0,0,1-.858.858Zm2.609-.35V18.107h9.107v1.051ZM6.893,15.13a.875.875,0,0,1-.63-.254.867.867,0,0,1,0-1.243.875.875,0,0,1,.63-.254.812.812,0,0,1,.6.263.867.867,0,0,1,0,1.226.812.812,0,0,1-.6.263Zm2.609-.35V13.729h9.107v1.051ZM6.876,10.751A.867.867,0,1,1,7.5,10.5.846.846,0,0,1,6.876,10.751ZM9.5,10.4V9.35h9.107V10.4Z"
                                transform="translate(-6 -9)" fill="#304aca" />
                        </svg>
                    </a>
                </div>
                <div class="date-change-fc px-2">
                    <button class="btn-prev" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4.758" height="7.735" viewBox="0 0 4.758 7.735">
                            <path id="arrow_forward_ios_FILL1_wght400_GRAD0_opsz48"
                                d="M16.88,11.354a.371.371,0,0,0,.1-.246.322.322,0,0,0-.1-.246L14.112,8.094,16.88,5.326a.343.343,0,0,0,.1-.242.348.348,0,0,0-.593-.259L13.3,7.909A.268.268,0,0,0,13.242,8a.275.275,0,0,0,0,.193.268.268,0,0,0,.062.088l3.085,3.084a.328.328,0,0,0,.242.1A.342.342,0,0,0,16.88,11.354Z"
                                transform="translate(-12.724 -4.224)" fill="#888ea8" stroke="#888ea8" stroke-width="1" />
                        </svg>
                    </button>
                    <input class="form-control" id="datepicker" type="text" placeholder="10/03/2022">
                    <div class="week-fc-input px-2" style="display: none;">
                        <label>
                            <input id="start_date" readonly class="form-control" placeholder="2022-10-02" />
                        </label>
                        <span class="px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4.99" height="1.77" viewBox="0 0 4.99 1.77">
                                <path id="Path_258" data-name="Path 258" d="M4.57-4.05v.77H.58v-.77Z"
                                    transform="translate(-0.08 4.55)" fill="#888ea8" stroke="#888ea8" stroke-width="1" />
                            </svg>
                        </span>
                        <label>
                            <input id="end_date" readonly class="form-control" placeholder="2022-10-08" />
                        </label>

                    </div>
                    <button class="btn-next" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4.758" height="7.736" viewBox="0 0 4.758 7.736">
                            <path id="arrow_forward_ios_FILL1_wght400_GRAD0_opsz48"
                                d="M13.325,11.355a.371.371,0,0,1-.1-.246.322.322,0,0,1,.1-.246l2.769-2.768L13.325,5.326a.343.343,0,0,1-.1-.242.348.348,0,0,1,.593-.259L16.9,7.91A.268.268,0,0,1,16.964,8a.275.275,0,0,1,0,.193.268.268,0,0,1-.062.088l-3.085,3.085a.328.328,0,0,1-.242.1A.342.342,0,0,1,13.325,11.355Z"
                                transform="translate(-12.724 -4.224)" fill="#888ea8" stroke="#888ea8"
                                stroke-width="1" />
                        </svg>
                    </button>
                </div>

                <div class="fc-day-cal px-2">
                    <select class="form-control" id="viewChange">
                        <option value="Day">Day</option>
                        <option value="Week">Week</option>
                        <option value="Month">Month</option>
                    </select>
                </div>
            </div>
            <div class="print-visible" id="calendar">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="input-block local-forms" style="z-index: -1;">
                        <small
                            class="text-danger font-weight-bold err_dropped_shape_data"></small>
                        <div class="drop_element" id="floorPlanContainer"
                            style="position: relative; border: 1px solid #ccc;">

                            <!-- Exist Dropped Elements -->
                            @foreach ($floor_plan->tables as $item)
                                <div class="ui-wrapper ui-draggable ui-draggable-handle"
                                    style="position: absolute; left: {{ $item->table_pos_y }}px; top: {{ $item->table_pos_x }}px; width: {{ $item->table_width }}px; height: {{ $item->table_height }}px;">
                                    <div class="ui-wrapper"
                                        style="overflow: hidden; position: static; width: {{ $item->table_width }}px; height: {{ $item->table_height }}px; top: {{ $item->table_pos_x }}px; left: {{ $item->table_pos_y }}px; margin: 0px;">
                                        <img src="{{ $item->table_info->element_info->normal_image }}"
                                            data-id="{{ $item->table_id }}"
                                            alt="element {{ $item->table_id }}"
                                            class="ui-draggable ui-draggable-handle ui-draggable-dragging dropped-shape ui-resizable"
                                            style="position: static; z-index: 1000; left: {{ $item->table_pos_y }}px; top: {{ $item->table_pos_x }}px; margin: 0px; resize: none; zoom: 1; display: block; height: {{ $item->table_height }}px; width: {{ $item->table_width }}px;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="curtain-overlay"></div>
    <div id="contextMenu" class="dropdown clearfix"></div>
    <div id="contextMenuEventChk" class="dropdown clearfix">
        <ul class="dropdown-menu dropNewEvent" role="menu" aria-labelledby="dropdownMenu">
            <li> <a tabindex="-1" style="cursor: pointer" class="btnConfirm" onclick="change_status(2)">Confirm</a>
            </li>
            <li> <a tabindex="-1" style="cursor: pointer" class="btnCompleted" onclick="change_status(4)">Completed</a>
            </li>
            @if (Auth::user()->hasPermissionTo('Update_Reservation'))
                <li> <a tabindex="-1" style="cursor: pointer" class="btnUpdate"
                        onclick="update_reservation()">Update</a></li>
                <li> <a tabindex="-1" style="cursor: pointer" class="btnUpdatePayment"
                        onclick="update_payment()">Update Payment</a></li>
            @endif
            <li>
                <div class="last-drop">
                    <a tabindex="-1" style="padding-top: 5px; cursor: pointer" class="btnCancel"
                        onclick="change_status(3)">Cancel</a>
                    <a tabindex="-1" class="btnReject" style="cursor: pointer" onclick="change_status(1)">Reject</a>
                    <a tabindex="-1" class="btnReservationDetails" onclick="view_details()"
                        style="padding-top: 5px; cursor: pointer">Reservation
                        Details</a>
                    <a tabindex="-1" class="btnClose" onclick="close_popup()"
                        style="padding-top: 5px; cursor: pointer">Close</a>
                </div>
            </li>
        </ul>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('calendarstyle/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('calendarstyle/js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/min/moment.min.js"></script>
    <script src="{{ asset('calendarstyle/js/mobiscroll.jquery.min.js') }}"></script>

    <script>
        var calendar;
        var reservation_id = '';
        $(document).ready(function() {
            // $('.location').select2()
            // $('.preference').select2()

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        $('#custom-inline-pick').mobiscroll().datepicker({
            controls: ['calendar'],
            // marked: marked,
            display: 'inline',
            theme: 'ios',
            themeVariant: 'light',
            controls: ['calendar'],
            display: 'inline',
            buttons: '',
            yearChange: false,
            calendarType: 'month',
            dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            onChange: function(event, inst) {
                var dateMobi = inst.getVal();
                var datetoday = new Date(dateMobi);
                var dd = datetoday.getDate();
                var mm = datetoday.getMonth() + 1;
                var yy = datetoday.getFullYear();

                if (dd < 10) {
                    dd = '0' + dd
                }
                if (mm < 10) {
                    mm = '0' + mm
                }

                var date = '' + yy + '-' + mm + '-' + dd + '';
                // console.log(date)

            },
        });
    </script>
@endsection

@extends('layouts.business')

@section('title')
    Manage Reservations
@endsection

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css'>
<link type="text/css" href="{{ asset('calendarstyle/css/style.css?v=') . time() }}" rel="stylesheet">
<link type="text/css" href="{{ asset('calendarstyle/css/mobiscroll.jquery.min.css') }}" rel="stylesheet">



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
                <!--
                        <a class="btn btn-blue serachClient text-uppercase" href="#" role="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-search">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            Search Client</a>
                        -->
            </div>

            <div class="mobi-calendar-cont">
                <div class="inline-picker" id="custom-inline-pick"></div>
            </div>

            @if (session()->get('_location_id'))
                <div class="input-block local-forms mt-5">
                    <label>Location</label>
                    <select name="location" class="form-control location input-height-40" id="location">
                        <option value=""></option>
                        @foreach ($locations as $item)
                            <option value="{{$item->id}}" {{session()->get('_location_id') == $item->id ? 'selected': ''}}>{{$item->location_name}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger font-weight-bold err_location"></small>
                </div>
            @endif

            @if (session()->get('_preference_id'))
                <div class="input-block local-forms mt-5">
                    <label>Preference</label>
                    <select name="preference" class="form-control preference input-height-40" id="preference">
                        <option value=""></option>
                        @foreach ($preferences as $item)
                            <option value="{{$item->id}}" {{session()->get('_preference_id') == $item->id ? 'selected': ''}}>{{$item->preference}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger font-weight-bold err_location"></small>
                </div>
            @endif
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

                <!--
                        <div class="fc-top-search">
                            <div class="input-group">
                                <div class="serach-input" style="display: none;">
                                    <input type="text" class="form-control" placeholder="What are you looking for?"
                                        aria-label="What are you looking for?" aria-describedby="search-btn">
                                    <button class="input-search-btn" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13.372" height="13.37"
                                            viewBox="0 0 13.372 13.37">
                                            <path id="search_FILL0_wght400_GRAD0_opsz48"
                                                d="M17.42,18.113,12.977,13.67a3.723,3.723,0,0,1-1.183.684,4.181,4.181,0,0,1-1.436.245,4.206,4.206,0,0,1-3.091-1.267A4.167,4.167,0,0,1,6,10.275,4.326,4.326,0,0,1,10.342,5.95a4.139,4.139,0,0,1,3.049,1.267,4.324,4.324,0,0,1,1.022,4.46,4.288,4.288,0,0,1-.71,1.267l4.46,4.426Zm-7.078-4.527a3.161,3.161,0,0,0,2.331-.971,3.324,3.324,0,0,0,0-4.679,3.161,3.161,0,0,0-2.331-.971,3.317,3.317,0,0,0-3.328,3.311,3.317,3.317,0,0,0,3.328,3.311Z"
                                                transform="translate(-5.5 -5.45)" fill="#7382f4" stroke="#7382f4"
                                                stroke-width="1" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn" type="button" id="search-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13.372" height="13.37"
                                            viewBox="0 0 13.372 13.37">
                                            <path id="search_FILL0_wght400_GRAD0_opsz48"
                                                d="M17.42,18.113,12.977,13.67a3.723,3.723,0,0,1-1.183.684,4.181,4.181,0,0,1-1.436.245,4.206,4.206,0,0,1-3.091-1.267A4.167,4.167,0,0,1,6,10.275,4.326,4.326,0,0,1,10.342,5.95a4.139,4.139,0,0,1,3.049,1.267,4.324,4.324,0,0,1,1.022,4.46,4.288,4.288,0,0,1-.71,1.267l4.46,4.426Zm-7.078-4.527a3.161,3.161,0,0,0,2.331-.971,3.324,3.324,0,0,0,0-4.679,3.161,3.161,0,0,0-2.331-.971,3.317,3.317,0,0,0-3.328,3.311,3.317,3.317,0,0,0,3.328,3.311Z"
                                                transform="translate(-5.5 -5.45)" fill="#dbdef8" stroke="#dbdef8"
                                                stroke-width="1" />
                                        </svg>

                                    </button>
                                </div>
                            </div>
                        </div>
                    -->
            </div>
            <div class="print-visible" id="calendar"></div>
        </div>
    </div>

    <div id="curtain-overlay"></div>
    <div id="contextMenu" class="dropdown clearfix"></div>
    <div id="contextMenuEventChk" class="dropdown clearfix">
        <ul class="dropdown-menu dropNewEvent" role="menu" aria-labelledby="dropdownMenu">
            <li> <a tabindex="-1" style="cursor: pointer" class="btnConfirm" onclick="change_status(2)">Confirm</a></li>
            <li> <a tabindex="-1" style="cursor: pointer" class="btnCompleted" onclick="change_status(4)">Completed</a></li>
            @if (Auth::user()->hasPermissionTo('Update_Reservation'))
                <li> <a tabindex="-1" style="cursor: pointer" class="btnUpdate" onclick="update_reservation()">Update</a></li>
                <li> <a tabindex="-1" style="cursor: pointer" class="btnUpdatePayment" onclick="update_payment()">Update Payment</a></li>
            @endif
            <li>
                <div class="last-drop">
                    <a tabindex="-1" style="padding-top: 5px; cursor: pointer" class="btnCancel" onclick="change_status(3)">Cancel</a>
                    <a tabindex="-1" class="btnReject" style="cursor: pointer" onclick="change_status(1)">Reject</a>
                    <a tabindex="-1" class="btnReservationDetails" onclick="view_details()" style="padding-top: 5px; cursor: pointer">Reservation
                        Details</a>
                    <a tabindex="-1" class="btnClose" onclick="close_popup()" style="padding-top: 5px; cursor: pointer">Close</a>
                </div>
            </li>
        </ul>
    </div>


    <!-- Edit Payroll model -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            {{-- <h4 id="offcanvasRightLabel" class="text-uppercase">Reservation Details</h4> --}}
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body _reservation_data_body">
            <!-- Dynamic body will show here -->

        </div>
    </div>
    <!-- END ------------------------------>

    <div class="modal fade" id="paymentStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Update Payment Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body _payment_update_div">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('calendarstyle/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('calendarstyle/js/main.js') }}"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.9.0/main.min.css' rel='stylesheet' />

    <script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/min/moment.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script> -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.9.0/main.min.js'></script>
    <script src="{{ asset('calendarstyle/js/mobiscroll.jquery.min.js') }}"></script>

    @include('business.reservation_calendar.calendar_script')
@endsection

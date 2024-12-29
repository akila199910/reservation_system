@extends('layouts.business')

@section('title')
    Manage Reservation
@endsection

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css'>
<link type="text/css" href="{{ asset('calendarstyle/css/style.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('calendarstyle/css/mobiscroll.jquery.min.css') }}" rel="stylesheet">

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">
                            <div class="p-3">
                                <div class="inline-picker"></div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">
                            <div id="loading"></div>
                            <div class="print-visible" id="calendar"></div>
                        </div>
                    </div>
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

    <script>
        var calendar;
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.inline-picker').mobiscroll().datepicker({
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
                //     onChange: function (event, inst) {
                //     var date = moment(inst.value, "YYYY-MM-DD");
                // },
            });

            var cal_location_id = $('.cal_location_id').val();

            var today_calendar = $('.today_calendar').val();

            window.setInterval(function() {
                // calendarLoad();
                calendar.refetchEvents();
                calendar.refetchResources();
            }, 300000);

            //Setup Calendar
            var calendarEl = document.getElementById('calendar');

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: '0352914974-fcs-1632120649',
                themeSystem: 'bootstrap4',
                initialView: 'resourceTimeGridDay',
                // timeZone: timezone,
                headerToolbar: {
                    left: 'title today',
                    center: '',
                    right: ''
                },
                titleFormat: {
                    year: 'numeric',
                    month: 'long'
                },
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                },
                nowIndicator: true,
                allDaySlot: false,
                selectable: false,
                initialDate: today,
                // selectHelper: true,
                resources: [{
                        id: 'a',
                        title: 'Room A'
                    },
                    {
                        id: 'b',
                        title: 'Room B'
                    },
                    {
                        id: 'c',
                        title: 'Room C'
                    },
                    // Add more resources as needed
                ],
                eventDidMount: function(info) {

                    if (info.view.type != "dayGridMonth") {
                        var is_global = info.event._def.extendedProps.is_global;

                        let title = info.el.getElementsByClassName(
                            "fc-event-title")[0];

                        let avatarImg = info.el.getElementsByClassName(
                            "fc-event-time")[0];

                        function insertAfter(referenceNode, newNode) {
                            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
                        }

                        if (info.event._def.extendedProps.is_clean == true) {
                            var el = document.createElement("div");
                            el.classList.add('room-clean');
                            el.classList.add(info.event._def.extendedProps.timeClass);
                            el.innerHTML = ` <strong>Room Cleaning Time</strong> `;
                            var div = info.el.getElementsByClassName("fc-event-main")[0];
                            insertAfter(div, el);
                        }


                        if (info.event._def.extendedProps.is_paid == true) {
                            var el = document.createElement("span");
                            el.classList.add('dollar-symbol');
                            el.innerHTML =
                                `  <img class="img-dollar"  src="calendarstyle/img/dollar.svg">`;
                            var div = info.el.getElementsByClassName("fc-event-time")[0];
                            insertAfter(div, el);
                        }

                        if (info.event._def.extendedProps.avatar && info.event._def.extendedProps
                            .alert ==
                            '' && is_global == false) {

                            var el = document.createElement("span");
                            el.innerHTML =
                                `  <img class="img-info-icon noteViewBtn" data-toggle="tooltip" data-placement="top" title="Click and View" src="calendarstyle/img/${info.event._def.extendedProps.avatar}">`;
                            var div = info.el.getElementsByClassName("fc-event-time")[0];
                            insertAfter(div, el);
                        }

                        if (info.event._def.extendedProps.alert && info.event._def.extendedProps
                            .avatar ==
                            '' && is_global == false) {

                            var el = document.createElement("span");
                            el.innerHTML =
                                `  <img class="img-info-icon alertViewBtn" data-toggle="tooltip" data-placement="top" title="Click and View" style="width:14px; height:14px" src="calendarstyle/img/${info.event._def.extendedProps.alert}">`;
                            var div = info.el.getElementsByClassName("fc-event-time")[0];
                            insertAfter(div, el);
                        }

                        if (info.event._def.extendedProps.alert && info.event._def.extendedProps
                            .avatar && is_global == false) {

                            var el = document.createElement("span");
                            el.innerHTML =
                                ` <img class="img-info-icon noteViewBtn" data-toggle="tooltip" data-placement="top" title="Click and View" src="calendarstyle/img/${info.event._def.extendedProps.avatar}"> <img class="img-info-icon alertViewBtn" data-toggle="tooltip" data-placement="top" style="width:14px; height:14px" title="Click and View" src="calendarstyle/img/${info.event._def.extendedProps.alert}">`;
                            var div = info.el.getElementsByClassName("fc-event-time")[0];
                            insertAfter(div, el);
                        }
                    }

                },
                events: [{
                        id: '1',
                        resourceId: 'a',
                        start: '2024-08-13T09:00:00',
                        end: '2024-08-13T10:00:00',
                        title: 'Event 1'
                    },
                    {
                        id: '2',
                        resourceId: 'b',
                        start: '2024-08-13T11:00:00',
                        end: '2024-08-13T12:00:00',
                        title: 'Event 2'
                    },
                    {
                        id: '3',
                        resourceId: 'c',
                        start: '2024-08-13T13:00:00',
                        end: '2024-08-13T14:00:00',
                        title: 'Event 3'
                    },
                    // Add more events as needed
                ],
                editable: false,
                eventResourceEditable: false,
                longPressDelay: 1,
                height: 'auto',
                eventLimit: true,
                eventOverlap: false,
                slotEventOverlap: false,
                views: {
                    dayGridMonth: {
                        dayMaxEventRows: 0,
                        moreLinkContent: function(args) {
                            return args.num + ' Appointments';
                        },
                        moreLinkClick: function(args) {
                            var datePrev = args.date;
                            // calendar.refetchResources();
                            $('#datepicker').mobiscroll('setVal', new Date(
                                datePrev
                                .toISOString()));
                            $('#custom-inline-pick').mobiscroll('setVal',
                                new Date(datePrev));
                            $('#viewChange').val('Day');
                            $('#contextMenuCheck').removeClass("contextOpened");
                            $('#contextMenuCheck').hide();
                            $('#contextMenuEventChk').removeClass(
                                "contextOpened");
                            $('#contextMenuEventChk').hide();
                            $('#datepicker').css('display', 'flex');
                            $('.week-fc-input').css('display', 'none');
                            $('.date-change-fc').css('display', 'flex');
                            return "resourceTimeGridDay";
                        },
                        dayHeaderFormat: {
                            weekday: 'long'
                        },
                    },
                    resourceTimeGridWeek: {
                        dayHeaderFormat: {
                            weekday: 'short',
                            day: 'numeric',
                            omitCommas: true
                        },
                        // dayHeaderContent: {html: '<i>'+   +'</i>'}
                        dayHeaderContent: (args) => {
                            return {
                                html: `${moment(args.date).format('ddd')}<span class="week-day-num d-block">${moment(args.date).format('D')}</span>`
                            }
                        }

                    }
                },
                slotDuration: '00:10:00',
                businessHours: {
                    daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // Monday - Thursday
                    startTime: '10:00', // a start time (10am in this example)
                    endTime: '22:00', // an end time (6pm in this example)
                },
                selectConstraint: "businessHours",
                dayMinWidth: 150,
                eventClick: function(info) {

                },

                select: function(arg) {


                },
                dateClick: function(info, jsEvent, startDate, endDate) {

                    $('#contextMenuCheck').removeClass("contextOpened");
                    $('#contextMenuCheck').hide();

                    $('#contextMenuEventChk').removeClass("contextOpened");
                    $('#contextMenuEventChk').hide();

                },
                eventDrop: function(info) {
                    // var infoResources = info.event.getResources();
                    // var resourceId = infoResources[0]._resource.id;
                    // var resourceTitle = infoResources[0]._resource.title;

                    // var status = info.event._def.extendedProps.status;
                    // var is_global = info.event._def.extendedProps.is_global;


                    // info.revert();
                },
                eventResize: function(info) {
                    // var infoResources = info.event.getResources();
                    // var resourceId = infoResources[0]._resource.id;
                    // var resourceTitle = infoResources[0]._resource.title;

                    // var status = info.event._def.extendedProps.status;
                    // var is_global = info.event._def.extendedProps.is_global;


                }
            });

            $('#viewChange').on('change', function() {
                $('#contextMenuEventChk').removeClass("contextOpened");
                $('#contextMenuEventChk').hide();
                $('#contextMenuCheck').removeClass("contextOpened");
                $('#contextMenuCheck').hide();
                $('#contextMenuEventChk').removeClass("contextOpened");
                $('#contextMenuEventChk').hide();
                $('#removeScheduleItemsCheckIn').modal('hide');
                if (this.value == 'Day') {
                    calendar.changeView('resourceTimeGridDay')
                    $('#datepicker').css('display', 'flex');
                    $('.week-fc-input').css('display', 'none');
                    $('.date-change-fc').css('display', 'flex');
                } else if (this.value == 'Week') {
                    calendar.changeView('resourceTimeGridWeek')
                    $('#datepicker').css('display', 'none');
                    $('.week-fc-input').css('display', 'flex');
                    $('.date-change-fc').css('display', 'flex');
                } else if (this.value == 'Month') {
                    $('#datepicker').css('display', 'none');
                    $('.week-fc-input').css('display', 'none');
                    $('.date-change-fc').css('display', 'none');
                    calendar.changeView('dayGridMonth')
                }
            });

            calendar.render();

            document.getElementsByClassName('fc-today-button')[0].addEventListener('click',
                function() {
                    var datePrev = calendar.getDate();

                    // calendar.refetchResources();
                    $('#datepicker').mobiscroll('setVal', new Date(datePrev
                        .toISOString()));
                    // $('.inline-picker').mobiscroll('setVal', new Date(dateNext
                    //         .toISOString()));
                    $('#custom-inline-pick').mobiscroll('setVal', new Date(datePrev));
                    //console.log('calendar.getDate()', calendar.getDate())

                    $('#contextMenuCheck').removeClass("contextOpened");
                    $('#contextMenuCheck').hide();

                    $('#contextMenuEventChk').removeClass("contextOpened");
                    $('#contextMenuEventChk').hide();

                    var d = new Date(datePrev),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2)
                        month = '0' + month;
                    if (day.length < 2)
                        day = '0' + day;
                    datenew = year + "-" + month + "-" + day;

                    var endDate = new Date(datenew);
                    endDate.setDate(endDate.getDate() +
                        6); //number  of days to add, e.x. 15 days
                    var dateFormated = endDate.toISOString().substr(0, 10);

                    $('#start_date').val(datenew);
                    $('#end_date').val(dateFormated);

                });

            document.getElementsByClassName('btn-prev')[0].addEventListener('click',
                function() {
                    calendar.prev();
                    var datePrev = calendar.getDate();
                    // calendar.refetchResources();
                    $('#datepicker').mobiscroll('setVal', new Date(datePrev
                        .toISOString()));
                    $('#custom-inline-pick').mobiscroll('setVal', new Date(datePrev));
                    //console.log('calendar.getDate()', calendar.getDate())

                    $('#contextMenuCheck').removeClass("contextOpened");
                    $('#contextMenuCheck').hide();

                    $('#contextMenuEventChk').removeClass("contextOpened");
                    $('#contextMenuEventChk').hide();

                    var d = new Date(datePrev),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2)
                        month = '0' + month;
                    if (day.length < 2)
                        day = '0' + day;
                    datenew = year + "-" + month + "-" + day;

                    var endDate = new Date(datenew);
                    endDate.setDate(endDate.getDate() +
                        6); //number  of days to add, e.x. 15 days
                    var dateFormated = endDate.toISOString().substr(0, 10);

                    $('#start_date').val(datenew);
                    $('#end_date').val(dateFormated);
                });

            document.getElementsByClassName('btn-next')[0].addEventListener('click',
                function() {
                    calendar.next();
                    var dateNext = calendar.getDate();
                    // calendar.refetchResources();
                    $('#datepicker').mobiscroll('setVal', new Date(dateNext
                        .toISOString()));
                    $('#custom-inline-pick').mobiscroll('setVal', new Date(dateNext));
                    //console.log('calendar.getDate()', calendar.getDate())

                    $('#contextMenuCheck').removeClass("contextOpened");
                    $('#contextMenuCheck').hide();

                    $('#contextMenuEventChk').removeClass("contextOpened");
                    $('#contextMenuEventChk').hide();

                    var d = new Date(dateNext),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2)
                        month = '0' + month;
                    if (day.length < 2)
                        day = '0' + day;
                    datenew = year + "-" + month + "-" + day;

                    var endDate = new Date(datenew);
                    endDate.setDate(endDate.getDate() +
                        6); //number  of days to add, e.x. 15 days
                    var dateFormated = endDate.toISOString().substr(0, 10);

                    $('#start_date').val(datenew);
                    $('#end_date').val(dateFormated);
                });

            // document.getElementById('curtain-btn').addEventListener('click', function() {
            //     this.classList.toggle('active');
            //     if (this.classList.contains('active')) {
            //         document.getElementById('curtain-overlay').style.display = 'block';
            //     } else {
            //         document.getElementById('curtain-overlay').style.display = 'none';
            //     }
            // });


            $(".fc-toolbar-chunk:first-child .fc-button-group").append(
                '<input type="text" id="datepicker" value="date"></input>');

            $('#contextMenu').on("click", "a", function(e) {
                e.preventDefault();
                $('#contextMenu').removeClass("contextOpened");
                $('#contextMenu').hide();
                $('body').removeClass('over-hidden');
            });

            $('body').on('click', function() {
                $('#contextMenu').hide();
                $('#contextMenu').removeClass("contextOpened");
                $('body').removeClass('over-hidden');
            });

            var dateDefault = calendar.getDate();
            var now = new Date();
            var customHeaderCal = $('#datepicker').mobiscroll().datepicker({
                theme: 'ios',
                themeVariant: 'light',
                touchUi: false,
                // min: yesterday,
                controls: ['calendar'],
                // defaultSelection: [new Date(2013, 2, 23)],
                // defaultValue: [new Date(2013, 2, 23)],
                // display: 'inline',
                buttons: '',
                yearChange: false,
                // weeks: '5',
                calendarType: 'month',
                dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
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
                    calendar.gotoDate(date); ///'2018-06-01'
                    $('#custom-inline-pick').mobiscroll('setVal', new Date(
                        date));
                    calendar.refetchResources();
                    $('#contextMenuCheck').removeClass("contextOpened");
                    $('#contextMenuCheck').hide();

                    $('#contextMenuEventChk').removeClass("contextOpened");
                    $('#contextMenuEventChk').hide();
                },

            }).mobiscroll('getInst');

            year = now.getFullYear();
            month = now.getMonth();

            var dateDefault = calendar.getDate();

            var scrollDivFix = $('.fc.fc-media-screen').offset().top;

            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });

            $('.custompickertime .form-control');

            $(".logo").hover(
                function() {
                    $(this).children().find('.collapse').collapse('show');
                },
                function() {
                    $(this).children().find('.collapse').collapse('hide');
                }
            );

            var datePrev = calendar.getDate();
            // calendar.refetchResources();
            $('#datepicker').mobiscroll('setVal', new Date(datePrev
                .toISOString()));
            // $('.inline-picker').mobiscroll('setVal', new Date(dateNext
            //         .toISOString()));
            $('#custom-inline-pick').mobiscroll('setVal', new Date(datePrev));
            //console.log('calendar.getDate()', calendar.getDate())

            //Calendar Setup End
        });
    </script>

    <script>


        document.addEventListener('DOMContentLoaded', function() {






        });
    </script>
@endsection

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
                calendar.gotoDate(date); ///'2018-06-01'
                $('#datepicker').mobiscroll('setVal', new Date(
                    date));
                calendar.refetchResources();
                $('#contextMenuCheck').removeClass("contextOpened");
                $('#contextMenuCheck').hide();

                $('#contextMenuEventChk').removeClass("contextOpened");
                $('#contextMenuEventChk').hide();
            },
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
            selectable: '{{ $selected_true }}',
            initialDate: today,
            // selectHelper: true,
            resources: '{{ route('business.calendar.get_resources') }}',
            eventDidMount: function(info) {

                console.log(info);

                if (info.view.type != "dayGridMonth") {
                    var is_global = info.event._def.extendedProps.is_global;

                    let title = info.el.getElementsByClassName(
                        "fc-event-title")[0];

                    let avatarImg = info.el.getElementsByClassName(
                        "fc-event-time")[0];

                    function insertAfter(referenceNode, newNode) {
                        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
                    }

                    // if (info.event._def.extendedProps.is_clean == true) {
                    //     var el = document.createElement("div");
                    //     el.classList.add('room-clean');
                    //     el.classList.add(info.event._def.extendedProps.timeClass);
                    //     el.innerHTML = ` <strong>Room Cleaning Time</strong> `;
                    //     var div = info.el.getElementsByClassName("fc-event-main")[0];
                    //     insertAfter(div, el);
                    // }

                    if (info.event._def.extendedProps.paid_status == true) {
                        var el = document.createElement("span");
                        el.classList.add('dollar-symbol');
                        el.innerHTML =
                            `  <img class="img-dollar"  src="../calendarstyle/img/dollar.svg">`;
                        var div = info.el.getElementsByClassName("fc-event-time")[0];
                        insertAfter(div, el);
                    }

                    // if (info.event._def.extendedProps.avatar && info.event._def.extendedProps
                    //     .alert ==
                    //     '' && is_global == false) {

                    //     var el = document.createElement("span");
                    //     el.innerHTML =
                    //         `  <img class="img-info-icon noteViewBtn" data-toggle="tooltip" data-placement="top" title="Click and View" src="calendarstyle/img/${info.event._def.extendedProps.avatar}">`;
                    //     var div = info.el.getElementsByClassName("fc-event-time")[0];
                    //     insertAfter(div, el);
                    // }

                    // if (info.event._def.extendedProps.alert && info.event._def.extendedProps
                    //     .avatar ==
                    //     '' && is_global == false) {

                    //     var el = document.createElement("span");
                    //     el.innerHTML =
                    //         `  <img class="img-info-icon alertViewBtn" data-toggle="tooltip" data-placement="top" title="Click and View" style="width:14px; height:14px" src="calendarstyle/img/${info.event._def.extendedProps.alert}">`;
                    //     var div = info.el.getElementsByClassName("fc-event-time")[0];
                    //     insertAfter(div, el);
                    // }

                    // if (info.event._def.extendedProps.alert && info.event._def.extendedProps
                    //     .avatar && is_global == false) {

                    //     var el = document.createElement("span");
                    //     el.innerHTML =
                    //         ` <img class="img-info-icon noteViewBtn" data-toggle="tooltip" data-placement="top" title="Click and View" src="calendarstyle/img/${info.event._def.extendedProps.avatar}"> <img class="img-info-icon alertViewBtn" data-toggle="tooltip" data-placement="top" style="width:14px; height:14px" title="Click and View" src="calendarstyle/img/${info.event._def.extendedProps.alert}">`;
                    //     var div = info.el.getElementsByClassName("fc-event-time")[0];
                    //     insertAfter(div, el);
                    // }
                }

            },
            events: '{{ route('business.calendar.get_events') }}',
            editable: false,
            eventResourceEditable: false,
            longPressDelay: 1,
            height: 'calc(100vh - 150px)',
            scrollTime: '07:00:00',
            eventLimit: false,
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
                startTime: '04:00', // a start time (10am in this example)
                endTime: '22:00', // an end time (6pm in this example)
            },
            selectConstraint: "businessHours",
            dayMinWidth: 150,
            eventClick: function(info) {
                $('.appointment_id').val('');

                reservation_id = info.event._def.extendedProps._id;
                var status = info.event._def.extendedProps.reservation_status;
                var complete_btn = info.event._def.extendedProps.complete_btn;
                var edit_button = info.event._def.extendedProps.edit_button;
                var btn_show = info.event._def.extendedProps.btn_show;
                var btn_pay_button = info.event._def.extendedProps.btn_pay_button;

                if (status == 0) {
                    $('.btnConfirm').show()
                    $('.btnReject').show()
                    $('.btnCompleted').hide()
                    $('.btnCancel').hide()
                } else if (status == 2) {
                    // $('.btnCompleted').show()
                    $('.btnUpdate').hide()
                    $('.btnCancel').show()
                    $('.btnConfirm').hide()
                    $('.btnReject').hide()
                } else {
                    $('.btnCompleted').hide()
                    $('.btnCancel').hide()
                    $('.btnConfirm').hide()
                    $('.btnReject').hide()
                }

                if (complete_btn == true) {
                    $('.btnCompleted').show()
                } else {
                    $('.btnCompleted').hide()
                }

                if (btn_show == true) {
                    $('.btnConfirm').show()
                    // $('.btnReject').show()
                    // $('.btnUpdate').show()
                    $('.btnCancel').show()
                } else {
                    $('.btnConfirm').hide()
                    $('.btnReject').hide()
                    $('.btnUpdate').hide()
                    $('.btnCancel').hide()
                }

                if (btn_show == true && status == 0) {
                    $('.btnConfirm').show()
                    $('.btnReject').show()
                } else {
                    $('.btnConfirm').hide()
                    $('.btnReject').hide()
                }

                // if (status == 0) {
                //     $('.btnUpdate').show()
                // } else {
                //     $('.btnUpdate').hide()
                // }

                if (status == 0 && edit_button == true) {
                    $('.btnUpdate').show()
                } else {
                    $('.btnUpdate').hide()
                }

                if (btn_pay_button == true) {
                    $('.btnUpdatePayment').show()
                } else {
                    $('.btnUpdatePayment').hide()
                }

                var $contextMenuCheck = $('#contextMenuCheck');
                if (info.jsEvent.target.classList.contains(
                        'fc-event-title-container')) {
                    $('#contextMenuEventChk').removeClass(
                        "contextOpened");
                    $('#contextMenuEventChk').hide();
                    //     var contextMenuCheckLeft = info.jsEvent.pageX - 150;
                    //   var contextMenuCheckTOp = info.jsEvent.pageY - 150;
                    $contextMenuCheck.addClass("contextOpened");

                    // Get the click position
                    var clickLeft = info.jsEvent.pageX;
                    var clickTop = info.jsEvent.pageY;

                    // Get the context menu dimensions and window width
                    var menuWidth = $contextMenuCheck.outerWidth();
                    var windowWidth = $(window).width();

                    // Adjust the position to prevent overflow
                    if (clickLeft + menuWidth > windowWidth) {
                        clickLeft = windowWidth - menuWidth -
                        10; // Position menu to the left if it overflows, with a 10px margin
                    }

                    $contextMenuCheck.css({
                        display: "block",
                        left: clickLeft,
                        top: clickTop
                        // left: info.jsEvent.pageX,
                        // top: info.jsEvent.pageY
                    });
                    $('body').addClass('over-hidden');
                    return false;
                } else {
                    $contextMenuCheck.removeClass("contextOpened");
                    $contextMenuCheck.hide();
                    $('body').removeClass('over-hidden');
                }
                // console.log(info.jsEvent.target)
                if (!info.jsEvent.target.classList.contains(
                        'fc-event-title-container') &&
                    !
                    info.jsEvent.target
                    .classList.contains('info-pop-event') && !info
                    .jsEvent
                    .target.classList
                    .contains('img-info-icon')) {
                    $('#contextMenuEventChk').addClass("contextOpened");

                    // Get the click position
                    var chkLeft = info.jsEvent.pageX;
                    var chkTop = info.jsEvent.pageY;

                    // Get the context menu dimensions and window width
                    var chkMenuWidth = $('#contextMenuEventChk').outerWidth();
                    var windowWidthChk = $(window).width();

                    // Adjust the position to prevent overflow
                    if (chkLeft + chkMenuWidth > windowWidthChk) {
                        chkLeft = windowWidthChk - chkMenuWidth - 10;
                    }


                    $('#contextMenuEventChk').css({
                        display: "block",
                        left: chkLeft,
                        top: chkTop
                    });
                    $('body').addClass('over-hidden');
                } else {
                    $('#contextMenuEventChk').removeClass(
                        "contextOpened");
                    $('#contextMenuEventChk').hide();
                    $('body').removeClass('over-hidden');
                }

                $(document).on('click', '.check-in-pop', function(e) {
                    e.stopPropagation();
                    $(this).toggleClass("active");
                });

                $('#contextMenu').removeClass("contextOpened");
                $('#contextMenu').hide();

                //     $('#contextMenuEventChk').removeClass("contextOpened");
                //     $('#contextMenuEventChk').hide();
            },
            select: function(arg) {
                console.log(arg.resource.id);
                var data = {
                    "_token": $('input[name=_token]').val(),
                    'start_time': arg.startStr,
                    'end_time': arg.endStr,
                    'table_id': arg.resource.id
                }

                $('#loader').show()

                createReservation(data)
            },
            dateClick: function(info, jsEvent, startDate, endDate) {
                // console.log(info);

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

        $(document).ready(function() {
            $("#search-btn").click(function() {
                $(".serach-input").toggleClass("show");
            });
            $(".input-search-btn").click(function() {
                $(".serach-input").removeClass("show");
            });
        });

        $(document).ready(function() {
            $('.shorcut-toggle-btn a').click(function() {
                $('.shorcut-toggle-btn').toggleClass('active');
                $('.calendar-sidemenu').toggleClass('active');
                $('.tonic-calendar-cont').toggleClass('expand');
                // calendar.render();
                // calendar.refetchEvents()
                setTimeout(() => {
                    calendar.render();
                }, 500);
            });

            $('.side-overlayclick, .mobile-shortcut-btn').on('click',
                function() {
                    $('.calendar-sidemenu').removeClass('active');
                    $('.tonic-calendar-cont').removeClass('expand');
                });

            $('.toggle-sidebar, #toggle_btn').on('click', function() {
                setTimeout(() => {
                    calendar.render();
                }, 500);
            });

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

        // Attach a scroll event listener to update context menu and appended div position
        document.querySelector('.fc-scroller.fc-scroller-liquid-absolute').addEventListener('scroll',
    function() {
            $('#contextMenuEventChk').removeClass("contextOpened");
            $('#contextMenuEventChk').hide();
        });

        // document.getElementById('my-today-button').addEventListener('click',
        //     function() {
        //         calendar.today();
        //     });

        $(window).on("load", function() {
            setTimeout(() => {
                calendar.render();
            }, 500);
        });

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

    $('#location').change(function(e) {
        e.preventDefault();

        var data = {
            'location_id': $(this).val()
        }

        $('#loader').show()

        $.ajax({
            type: "POST",
            url: "{{ route('business.calendar.change_location') }}",
            data: data,
            dataType: "JSON",
            success: function(response) {

                $('#preference').html('')
                $('#preference').append('<option value=""></option>');
                $.each(response.data, function(key, item) {
                    $('#preference').append('<option value="' + item.id + '">' + item
                        .preference + '</option>');
                });
                $('#loader').hide()

                //refresh the calendar event and resources
                calendar.refetchEvents();
                calendar.refetchResources();
            },
            error: function(data) {
                someThingWrong();
            }
        });
    });

    $('#preference').change(function(e) {
        e.preventDefault();

        var data = {
            'location_id': $('#location').val(),
            'preference_id': $(this).val()
        }

        $('#loader').show()

        $.ajax({
            type: "POST",
            url: "{{ route('business.calendar.change_preference') }}",
            data: data,
            dataType: "JSON",
            success: function(response) {

                $('#loader').hide()

                //refresh the calendar event and resources
                calendar.refetchEvents();
                calendar.refetchResources();
            },
            error: function(data) {
                someThingWrong();
            }
        });
    });

    function view_details() {
        var data = {
            'reservation_id': reservation_id
        }

        $('#loader').show()

        $.ajax({
            type: "POST",
            url: "{{ route('business.calendar.get_detail') }}",
            data: data,
            success: function(response) {

                $('#loader').hide()

                $('._reservation_data_body').html('')
                $('._reservation_data_body').html(response)

                var myOffcanvas = document.getElementById('offcanvasRight')
                var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
                bsOffcanvas.show()
            },
            error: function(data) {
                someThingWrong();
            }
        });
    }

    function change_status(status) {
        var data = {
            'id': reservation_id,
            'status': status
        }

        if (status == 1) {
            var message = 'Do you want to Reject the Selected Reservation?';
        }

        if (status == 2) {
            var message = 'Do you want to Confirm the Selected Reservation?';
        }

        if (status == 3) {
            var message = 'Do you want to Cancel the Selected Reservation?';
        }

        if (status == 4) {
            var message = 'Do you want to Completed the Selected Reservation?';
        }

        $.confirm({
            theme: 'modern',
            columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
            icon: 'far fa-question-circle text-danger',
            title: 'Are you Sure!',
            content: message,
            type: 'red',
            autoClose: 'cancel|10000',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-green',
                    action: function() {
                        update_reservation_status(data)
                    }
                },

                cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-red',
                    action: function() {

                    }
                },
            }
        });
    }

    function update_reservation_status(data) {
        $('#loader').show()

        $.ajax({
            type: "POST",
            url: "{{ route('business.reservation.change_status') }}",
            data: data,
            success: function(response) {
                $("#loader").hide();

                if (response.status == false) {
                    $.confirm({
                        theme: 'modern',
                        columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
                        icon: 'fas fa-info-circle text-info',
                        title: 'Failed!',
                        content: response.message,
                        type: 'red',
                        autoClose: 'cancel|10000',
                        buttons: {
                            confirm: {
                                text: 'OK',
                                btnClass: 'btn-green',
                                action: function() {
                                    if (response.route != '') {
                                        update_reservation()
                                    }
                                }
                            },

                            cancel: {
                                text: 'Cancel',
                                btnClass: 'btn-red',
                                action: function() {

                                }
                            },
                        }
                    });
                } else {
                    //refresh the calendar event and resources
                    calendar.refetchEvents();
                    calendar.refetchResources();

                    $('#contextMenuCheck').removeClass("contextOpened");
                    $('#contextMenuCheck').hide();

                    $('#contextMenuEventChk').removeClass("contextOpened");
                    $('#contextMenuEventChk').hide();
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
    }

    function createReservation(data) {

        $.ajax({
            type: "POST",
            url: "{{ route('business.calendar.validations') }}",
            data: data,
            success: function(response) {
                $('#loader').hide()
                if (response.status == false) {
                    errorPopup(response.message, '')
                } else {
                    $('._reservation_data_body').html('')
                    $('._reservation_data_body').html(response)
                    var myOffcanvas = document.getElementById('offcanvasRight')
                    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
                    bsOffcanvas.show()
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

    }

    function update_reservation() {
        var data = {
            'reservation_id': reservation_id
        }

        $('#loader').show()

        $.ajax({
            type: "POST",
            url: "{{ route('business.calendar.update_view') }}",
            data: data,
            success: function(response) {
                $('#loader').hide()
                if (response.status == false) {
                    errorPopup(response.message, '')
                } else {
                    $('._reservation_data_body').html('')
                    $('._reservation_data_body').html(response)
                    var myOffcanvas = document.getElementById('offcanvasRight')
                    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
                    bsOffcanvas.show()

                    $('#contextMenuCheck').removeClass("contextOpened");
                    $('#contextMenuCheck').hide();

                    $('#contextMenuEventChk').removeClass("contextOpened");
                    $('#contextMenuEventChk').hide();
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
    }

    function update_payment() {
        $.confirm({
            theme: 'modern',
            columnClass: 'col-lg-6 col-md-8 col-sm-10 col-12',
            icon: 'far fa-question-circle text-danger',
            title: 'Are you Sure!',
            content: 'Do you want to change the payment status to the Selected Reservation?',
            type: 'red',
            autoClose: 'cancel|10000',
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-green',
                    action: function() {
                        $("#loader").show();
                        var data = {
                            "_token": $('input[name=_token]').val(),
                            "id": reservation_id,
                            "view" : "calendar"
                        }
                        $.ajax({
                            type: "POST",
                            url: "{{ route('business.reservation.get_payment_status') }}",
                            data: data,
                            success: function(response) {
                                $("#loader").hide();
                                $('._payment_update_div').html('')
                                $('._payment_update_div').html(response)
                                $('#paymentStatusModal').modal('show')
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

                    }
                },

                cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-red',
                    action: function() {

                    }
                },
            }
        });
    }

    function close_popup() {
        $('#contextMenuCheck').removeClass("contextOpened");
        $('#contextMenuCheck').hide();

        $('#contextMenuEventChk').removeClass("contextOpened");
        $('#contextMenuEventChk').hide();

    }


</script>

! function($) {
    "use strict";

    var CalendarApp = function() {
        this.$body = $("body")
        this.$calendar = $('#calendar'),
            this.$event = ('#calendar-events div.calendar-events'),
            this.$categoryForm = $('#add-new-event form'),
            this.$extEvents = $('#calendar-events'),
            this.$modal = $('#my-event'),
            this.$saveCategoryBtn = $('.save-category'),
            this.$calendarObj = null
    };


    /* on drop */
    CalendarApp.prototype.onDrop = function(eventObj, date) {
            var $this = this;
            // retrieve the dropped element's stored Event Object
            var originalEventObject = eventObj.data('eventObject');
            var $categoryClass = eventObj.attr('data-class');
            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);
            // assign it the date that was reported
            copiedEventObject.start = date;
            if ($categoryClass)
                copiedEventObject['className'] = [$categoryClass];
            // render the event on the calendar
            $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                eventObj.remove();
            }
        },
        /* on click on event */
        CalendarApp.prototype.onEventClick = function(calEvent, jsEvent, view) {
            var $this = this;

            console.log(calEvent , "a");

            var id = calEvent.id;
            var date = calEvent.date;

            var holiday_id = calEvent.holiday;
            var leave_id = calEvent.leave;
            
            let time = calEvent.title;
            time = time.split('-');
            var start_time = '';
            var end_time = '';

            var disabled = '';

            if(calEvent.start_time.includes('pm') ) {

               start_time =  calEvent.start_time.replace(' pm','');

            }else if(calEvent.start_time.includes('am')) {

                start_time =  calEvent.start_time.replace(' am','');

            }

            if(calEvent.end_time.includes('pm')) {
                end_time =  calEvent.end_time.replace(' pm','');

            }else if(calEvent.end_time.includes('am')) {

                end_time =  calEvent.end_time.replace(' am','');
            }

            if(holiday_id == 1 || leave_id == 1) {
                disabled = 'disabled';
                start_time = '';
                end_time = '';
            }else{
                disabled = '';
                start_time = start_time;
                end_time = end_time;
            }


            var form = $(`
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <label class='control-label'>Start Time</label>
                            <input class='form-control' type='time' name='edit_start_time' id="edit_start_time" value="`+ start_time  +`" `+disabled+`>
                        </div>
                        <div class="col-md-6">
                            <label class='control-label'>End Time</label>
                            <input class='form-control'  type='time' name='edit_end_time' id="edit_end_time" value="`+ end_time +`"  `+disabled+`>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="exampleRadios" id="edit_holiday" `+ (holiday_id == 1 ? 'checked' : '')  +`>
                                <label class="form-check-label" for="edit_holiday"> Holiday </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="exampleRadios" id="edit_leave" `+ (leave_id == 1 ? 'checked' : '')  +`>
                                <label class="form-check-label" for="edit_leave"> Leave </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm rounded mt-2 ml-2" style="float:right"> Save </button>
                    <button type="button" role="button" onclick="deleteSchedule(`+id+`)" class="btn btn-danger btn-sm rounded mt-2 mr-2" style="float:right"> Delete </button>
                </form>
            `);
            $this.$modal.modal({
                backdrop: 'static'
            });
            $this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.delete-event').unbind('click').click(function() {
                $this.$calendarObj.fullCalendar('removeEvents', function(ev) {
                    return (ev._id == calEvent._id);
                });
                $this.$modal.modal('hide');
            });
            $this.$modal.find('form').on('submit', function(e) {
                e.preventDefault();
                
                // update schedule

                var start_time =$("#edit_start_time").val();
                var end_time  = $("#edit_end_time").val();
                var holiday = 0;
                var leave = 0;

                if( $("#edit_holiday").is(':checked') ) {
                    holiday = 1;
                }else{
                    holiday = 0;
                }

                if( $("#edit_leave").is(':checked') ) {
                    leave = 1;
                }else{
                    leave = 0;
                }

                var form = {
                    staff_id: staff_id,
                    schedule_date: date,
                    start_time:start_time,
                    end_time:end_time,
                    is_holiday:holiday,
                    is_leave:leave,
                    id: id,
                }

                $.ajax({
                    type: "post",
                    url: schedule_route,
                    data: form,
                    async:false,
                    success: function (data) {
                        if (data['success'] == true) {
                            
                            $this.$modal.modal('hide');

                            toastr.success(data.message, { timeOut: 5000 });
                        } else {
                            
                        }
                    },
                    error:function(e) {
                        console.log(e);
                    }
                });


                
                return false;
            });
        },
        /* on select */
        CalendarApp.prototype.onSelect = function(start, end, allDay) {
            var $this = this;
            var selected_date = start.format("YYYY-MM-DD");
            var title_text = ``;
            var class_name = ``;
            $this.$modal.modal({
                backdrop: 'static'
            });
            var form = $(`<form id='schedule_form'>
                <div class="row">
                    <div class="col-md-6">
                        <label class='control-label'>Start Time</label>
                        <input class='form-control'  type='time' name='start_time' id="start_time"/>
                    </div>
                    <div class="col-md-6">
                        <label class='control-label'>End Time</label>
                        <input class='form-control'  type='time' name='end_time' id="end_time"/>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" onchange="disabledTime(this.value)" name="exampleRadios" id="holiday">
                            <label class="form-check-label" for="holiday"> Holiday </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" onchange="disabledTime(this.value)" name="exampleRadios" id="leave">
                            <label class="form-check-label" for="leave"> Leave </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm rounded mt-2" style="float:right"> Save </button>
            </form>`);
            $this.$modal.find('.delete-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function() {
                form.submit();
            });
            $this.$modal.find('form').on('submit', function(e) {
                e.preventDefault();

                // add schedule
                var start_time = $("#start_time").val();
                var end_time = $("#end_time").val();
                   
                var st_dt = selected_date+" "+start_time;
                var ed_dt = selected_date+" "+end_time;

                var st_tm = moment(st_dt).format("x");
                var ed_tm = moment(ed_dt).format("x");

                st_tm = new Date(parseInt(st_tm));
                ed_tm = new Date(parseInt(ed_tm));

                var sh_st_t = moment(st_dt).format("hh:mm a");
                var sh_ed_t = moment(ed_dt).format("hh:mm a");

                
                var holiday = 0;
                var leave = 0;

                if( $("#holiday").is(':checked') ) {

                    holiday = 1;
                    title_text = 'holiday';
                    class_name = 'bg-warning';
                    start_time = '00:00';
                    end_time = '00:00';
                    $("#start_time").val(" ");
                    $("#end_time").val(" ");

                }else if ($("#leave").is(':checked')  ){
                    leave = 1;
                    title_text = 'leave';
                    class_name = 'bg-danger';
                    start_time = '00:00';
                    end_time = '00:00';

                    $("#start_time").val(" ");
                    $("#end_time").val(" ");

                }else{
                    title_text = start_time +'-'+end_time;
                    class_name = 'bg-success';
                    start_time = start_time;
                    end_time = end_time;
                }

                var form = {
                    staff_id: staff_id,
                    schedule_date: selected_date,
                    start_time:start_time,
                    end_time:end_time,
                    is_holiday:holiday,
                    is_leave:leave,
                    id: '',
                }

                $.ajax({
                    type: "post",
                    url: schedule_route,
                    data: form,
                    async:false,
                    success: function (data) {
                        if(data.status_code == 200 && data.success == true) {
                            
                            toastr.success(data.message, { timeOut: 5000 });

                            $this.$calendarObj.fullCalendar('renderEvent', {
                                title: title_text,
                                start: st_tm ,
                                id:data.id,
                                start_time : sh_st_t,
                                end_time:sh_ed_t,
                                date:selected_date,
                                holiday:holiday,
                                leave:leave,
                                allDay: true,
                                displayEventTime : false,
                                className: class_name,
                                
                            }, true);
                            $this.$modal.modal('hide');

                            var url = window.location.href;

                            if(url.includes('#staff-schedule')) {
                                location.reload();
                            }else{
                                window.location.replace(url + "#staff-schedule");
                                location.reload();
                            }
                            
                            

                        }else{
                            toastr.error(data.message, { timeOut: 5000 });
                        }
                        
                    }
                });

                
                return false;

            });
            $this.$calendarObj.fullCalendar('unselect');
        },
        CalendarApp.prototype.enableDrag = function() {
            //init events
            $(this.$event).each(function() {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);
                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            });
        }
    /* Initializing */
    CalendarApp.prototype.init = function() {
            this.enableDrag();
            /*  Initialize the calendar  */
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            var form = '';
            var today = new Date($.now());
            // console.log($.now() + 506800000)
            var defaultEvents = [];
            
            // get_all_schedules(defaultEvents);

            // console.log( defaultEvents , "default" );

            var $this = this;
            $this.$calendarObj = $this.$calendar.fullCalendar({
                slotDuration: '00:15:00',
                /* If we want to split day time each 15minutes */
                minTime: '08:00:00',
                maxTime: '19:00:00',
                defaultView: 'month',
                handleWindowResize: true,

                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: defaultEvents,
                // editable: true,
                // droppable: true, // this allows things to be dropped onto the calendar !!!
                eventLimit: true, // allow "more" link when too many events
                selectable: true,
                // drop: function(date) { $this.onDrop($(this), date); },
                select: function(start, end, allDay) { $this.onSelect(start, end, allDay); },
                eventClick: function(calEvent, jsEvent, view) { $this.onEventClick(calEvent, jsEvent, view); }

            });

            //on new event
            this.$saveCategoryBtn.on('click', function() {
                var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
                var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
                if (categoryName !== null && categoryName.length != 0) {
                    $this.$extEvents.append('<div class="calendar-events m-b-20" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fa fa-circle text-' + categoryColor + ' m-r-10" ></i>' + categoryName + '</div>')
                    $this.enableDrag();
                }

            });
        },

        //init CalendarApp
        $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

}(window.jQuery),

//initializing CalendarApp
$(window).on('load', function() {

    $.CalendarApp.init()
});


function get_all_schedules(defaultEvents) {
    $.ajax({
        type: "get",
        url: getStaffSchedule,
        data: {staff_id: staff_id},
        dataType: 'json',
        cache: false,
        async:false,
        success: function (data) {
            // console.log(data.data , "calender");
            var title_text = '';
            if(data.success == true){
                var timings = data.data;
                if(timings.length > 0){
                    for(var i in timings){

                        var timeObj = new Object();

                        var st_dt = timings[i].schedule_date+" "+timings[i].start_time;
                        var ed_dt = timings[i].schedule_date+" "+timings[i].end_time;
    
                        var st_tm = moment(st_dt).format("x");
                        var ed_tm = moment(ed_dt).format("x");
    
                        st_tm = new Date(parseInt(st_tm));
                        ed_tm = new Date(parseInt(ed_tm));
    
                        var sh_st_t = moment(st_dt).format("hh:mm a");
                        var sh_ed_t = moment(ed_dt).format("hh:mm a");

                        var class_name = '';

                        if(timings[i].is_holiday == 1) {
                            title_text = 'Holiday';
                            class_name = 'bg-warning';
                        }else if(timings[i].is_leave == 1){
                            title_text = 'Leave';
                            class_name = 'bg-danger';
                        }else{
                            title_text = sh_st_t + '-' + sh_ed_t;
                            class_name = 'bg-success';
                        }

                        timeObj.title = title_text;
                        timeObj.start = st_tm;
                        timeObj.id = timings[i].id;
                        timeObj.date = timings[i].schedule_date;
                        timeObj.holiday = timings[i].is_holiday;
                        timeObj.start_time = sh_st_t;
                        timeObj.end_time = sh_ed_t;
                        timeObj.leave = timings[i].is_leave;
                        timeObj.allDay = true;
                        timeObj.displayEventTime = false;
                        timeObj.className = class_name;
                        defaultEvents.push(timeObj)
                    }
                    
                }
            }
        }
    });
}


function deleteSchedule(id) {
    $.ajax({
        type: "post",
        url: delete_schedule,
        data:{id:id},
        dataType: 'json',
        success: function (data) {
            console.log(data);
            toastr.success(data.message, { timeOut: 5000 });
            location.reload();
        },error:function(e) {
            console.log(e);
        }
    });
}


function disabledTime(value) {

    if(value == 'on') {
        $("#start_time").attr('disabled',true);
        $("#end_time").attr('disabled',true);
    }
}
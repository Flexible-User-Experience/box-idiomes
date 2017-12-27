jQuery(function () {
    jQuery('#calendar-holder').fullCalendar({
        header: {
            left: 'prev,next,today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        height: 600,
        locale: 'ca',
        firstDay: 1,
        lazyFetching: false,
        editable: true,
        navLinks: true,
        eventLimit: true,
        businessHours: false,
        displayEventTime: true,
        weekNumbers: false,
        defaultView: 'agendaWeek',
        events: {
            url: Routing.generate('ancarebeca_full_calendar_load'),
            type: 'POST',
            data: {},
            error: function(data) {
                console.log('error!', data.responseText);
            }
        },
        eventRender: function(event, element) {
            var eventsdate = moment(event.start).format('HH:mm');
            var eventedate = moment(event.end).format('HH:mm');

            element.find('.fc-time').html(eventsdate + " - " + eventedate + "<br>");
            console.log(event);
        }
    });
});

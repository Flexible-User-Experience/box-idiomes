jQuery(function () {
    jQuery('#calendar-holder').fullCalendar({
        header: {
            left: 'prev,next,today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        locale: 'ca',
        // themeSystem: 'bootstrap3',
        firstDay: 1,
        lazyFetching: false,
        editable: true,
        navLinks: true,
        eventLimit: true,
        businessHours: false,
        displayEventTime: true,
        weekNumbers: false,
        defaultView: 'agendaWeek',
        timeFormat: {
            agenda: 'H:mm',
            '': 'H:mm'
        },
        eventSources: [
            {
                url: Routing.generate('ancarebeca_full_calendar_load'),
                type: 'POST',
                data: {},
                error: function () {}
            }
        ]
    });
});

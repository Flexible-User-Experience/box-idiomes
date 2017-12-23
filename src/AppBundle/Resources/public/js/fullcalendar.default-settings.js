$(function () {
    $('#calendar-holder').fullCalendar({
        header: {
            left: 'prev, next, today',
            center: 'title',
            right: 'month, agendaWeek, agendaDay, listWeek'
        },
        locale: 'ca',
        lazyFetching: true,
        defaultDate: '2017-11-12',
        editable: true,
        navLinks: true,
        eventLimit: true,
        businessHours: false,
        displayEventTime: true,
        weekNumbers: false,
        defaultView: 'agendaWeek',
        timeFormat: {
            agenda: 'h:mmt',
            '': 'h:mmt'
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

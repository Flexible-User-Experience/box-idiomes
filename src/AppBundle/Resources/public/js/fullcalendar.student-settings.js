jQuery(function () {
    jQuery('#calendar-holder').fullCalendar({
        header: {
            left: 'prev today next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay listWeek'
        },
        views: {
            agendaWeek: {
                allDaySlot: true,
                slotLabelFormat: 'HH:mm',
                scrollTime: '08:00:00',
                minTime: '06:00:00',
                maxTime: '22:00:00'
            },
            agendaDay: {
                allDaySlot: true,
                slotLabelFormat: 'HH:mm',
                scrollTime: '08:00:00',
                minTime: '06:00:00',
                maxTime: '22:00:00'
            }
        },
        height: 750,
        locale: 'ca',
        timeFormat: 'HH:mm',
        firstDay: 1,
        lazyFetching: false,
        editable: true,
        navLinks: true,
        eventLimit: true,
        businessHours: false,
        displayEventTime: true,
        fixedWeekCount: false,
        weekNumbers: false,
        defaultView: 'month',
        themeSystem: 'bootstrap3',
        googleCalendarApiKey: 'AIzaSyCZZYZV-LqX2qDtggiEo1GmeNhxe3SAhfI',
        eventSources: [
            {
                url: Routing.generate('ancarebeca_full_calendar_load'),
                type: 'POST',
                data: {},
                error: function(data) {
                    console.log('error!', data.responseText);
                }
            }
        ]
    });
});

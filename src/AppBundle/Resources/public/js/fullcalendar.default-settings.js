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
                scrollTime: '08:00:00',
                minTime: '06:00:00',
                maxTime: '22:00:00'
            },
            agendaDay: {
                allDaySlot: true,
                scrollTime: '08:00:00',
                minTime: '06:00:00',
                maxTime: '22:00:00'
            }
        },
        height: 750,
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
        themeSystem: 'bootstrap3',
        googleCalendarApiKey: 'AIzaSyCZZYZV-LqX2qDtggiEo1GmeNhxe3SAhfI',
        eventSources: [
            {
                googleCalendarId: 'es.spain#holiday@group.v.calendar.google.com',
                backgroundColor: '#FED3D7',
                textColor: '#FF0000',
                color: '#FED3D7'
            },
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

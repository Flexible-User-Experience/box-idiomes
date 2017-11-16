$(document).ready(function() {
    $('#calendar-holder').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listWeek'
        },
        defaultDate: '2017-11-12',
        editable: true,
        navLinks: true,
        eventLimit: true,
        businessHours: false,
        displayEventTime: true,
        weekNumbers: true,
        defaultView: 'agendaWeek',
        events: {
            url: Routing.generate('fullcalendar_loader'),
            error: function() {
                console.log('Error full calendar loader');
            }
        }
        // events: [
        //     {
        //         title: 'All Day Event',
        //         start: '2017-11-01'
        //     },
        //     {
        //         title: 'Long Event',
        //         start: '2017-11-07',
        //         end: '2017-11-10'
        //     },
        //     {
        //         id: 999,
        //         title: 'Repeating Event',
        //         start: '2017-11-09T16:00:00'
        //     },
        //     {
        //         id: 999,
        //         title: 'Repeating Event',
        //         start: '2017-11-16T16:00:00'
        //     },
        //     {
        //         title: 'Conference',
        //         start: '2017-11-11',
        //         end: '2017-11-13'
        //     },
        //     {
        //         title: 'Meeting',
        //         start: '2017-11-12T10:30:00',
        //         end: '2017-11-12T12:30:00'
        //     },
        //     {
        //         title: 'Meeting',
        //         start: '2017-11-12T11:30:00',
        //         end: '2017-11-12T13:30:00'
        //     },
        //     {
        //         title: 'Meeting',
        //         start: '2017-11-12T13:30:00',
        //         end: '2017-11-12T14:30:00'
        //     },
        //     {
        //         title: 'Meeting',
        //         start: '2017-11-12T10:30:00',
        //         end: '2017-11-12T12:30:00'
        //     },
        //     {
        //         title: 'Meeting',
        //         start: '2017-11-12T14:30:00',
        //         end: '2017-11-12T15:30:00'
        //     },
        //     {
        //         title: 'Meeting',
        //         start: '2017-11-12T16:30:00',
        //         end: '2017-11-12T17:30:00'
        //     },
        //     {
        //         title: 'Lunch',
        //         start: '2017-11-12T12:00:00'
        //     },
        //     {
        //         title: 'Meeting',
        //         start: '2017-11-12T14:30:00'
        //     },
        //     {
        //         title: 'Happy Hour',
        //         start: '2017-11-12T17:30:00'
        //     },
        //     {
        //         title: 'Dinner',
        //         start: '2017-11-12T20:00:00'
        //     },
        //     {
        //         title: 'Birthday Party',
        //         start: '2017-11-13T07:00:00'
        //     },
        //     {
        //         title: 'Click for Google',
        //         url: 'http://google.com/',
        //         start: '2017-11-28'
        //     }
        // ]
        // header: {
        //     left: 'prev,next today',
        //     center: 'title',
        //     right: 'month, agendaWeek, agendaDay, listWeek'
        // },
        // editable: true,
        // navLinks: true, // can click day/week names to navigate views
        // eventLimit: true, // allow "more" link when too many events
        // events: {
        //     url: Routing.generate('fullcalendar_loader'),
        //     error: function() {
        //         $('#script-warning').show();
        //     }
        // },
        // loading: function(bool) {
        //     $('#loading').toggle(bool);
        // },
        // locale: 'ca',
        // lazyFetching: true,
        // timeFormat: {
        //     // for agendaWeek and agendaDay
        //     agenda: 'h:mmt',    // 5:00 - 6:30
        //
        //     // for all other views
        //     '': 'h:mmt'         // 7p
        // },
        // eventSources: [
        //     {
        //         url: Routing.generate('fullcalendar_loader'),
        //         type: 'POST',
        //         // A way to add custom filters to your event listeners
        //         data: {
        //         },
        //         error: function() {
        //             //alert('There was an error while fetching Google Calendar!');
        //         }
        //     }
        // ]
    });
});

@extends('student_layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css' rel='stylesheet'>
    <style>
        .fc-event {
            cursor: context-menu;
        }
    </style>
@endpush
@section('content')
    <div id="calendar"></div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales-all.js"></script>
    <script>
        $(document).ready(function() {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap',
                locale: 'vi',
                timeZone: 'Asia/Ho_Chi_Minh',
                firstDay: 1, //start from monday
                headerToolbar: {
                    start: 'today prev,next',
                    center: 'title',
                    end: 'timeGridDay,timeGridWeek,dayGridMonth',
                },
                initialView: 'timeGridDay',
                expandRows: true,
                eventTimeFormat: { // like '14:30'
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                },
                handleWindowResize: true,
                stickyHeaderDates: true,
                hiddenDays: [0],
                dayMaxEventRows: true,
                views: {
                    timeGrid: {
                        dayMaxEventRows: 5,
                        eventMaxStack: 2,
                    }
                },
                nowIndicator: true,

                events: {
                    url: '{{ route('student.learning_schedule.get_schedules') }}',
                },
                eventDisplay: "block",
                selectable: true,

                eventDidMount: function(info) {
                    console.log(info);
                    $(info.el).popover({
                        title: info.event.title,
                        placement: 'top',
                        trigger: 'hover',
                        html: true,
                        content: `<ul>
                            <li>
                                Thời gian học: ${info.event.extendedProps.start} -  ${info.event.extendedProps.end} 
                            </li>
                            <li>
                                Giảng viên: ${info.event.extendedProps.lecturer}     
                            </li>
                            </ul>`,
                        container: 'body'
                    });
                },
            });
            calendar.render();
        });
    </script>
@endpush

@extends('admin_layout.master')
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
    <br>
    <a href="{{ route('admin.exams.index') }}">
        <button type="button" class="btn btn-outline-primary dripicons-arrow-thin-left mt-3 mb-5">
            Quay lại
        </button>
    </a>
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
                    end: 'dayGridMonth,timeGridWeek',
                },
                initialView: 'dayGridMonth',
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
                        dayMaxEventRows: 3,
                        eventMaxStack: 2,
                        eventMinHeight: 50,
                    }
                },
                nowIndicator: true,
                selectable: true,

                events: {
                    url: '{{ route('admin.exams.get_exams') }}',
                },

                //view exam info
                eventDidMount: function(info) {
                    $(info.el).popover({
                        title: info.event.title,
                        placement: 'top',
                        trigger: 'hover',
                        html: true,
                        content: `<ul class="form-group">
                            <li>
                                Thời gian bắt đầu: ${info.event.extendedProps.startTime}     
                            </li>
                            <li>
                                Hình thức: ${info.event.extendedProps.type}     
                            </li>
                            <li>
                                Giám thị: ${info.event.extendedProps.proctorName}
                            </li>
                            <li>
                                Người chấm: ${info.event.extendedProps.examinerName}         
                            </li>
                        </ul>
                        `,
                        container: 'body'
                    });
                },

                //trigger insert exams modal
                dateClick: function(info) {
                    // console.log(info.dateStr);
                },

                eventClick: function(info) {
                    console.log(info.event.extendedProps);
                },
            });
            calendar.render();
        });
    </script>
@endpush

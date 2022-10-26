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
        function recreateSelectModules(modules) {
            let html = ``;
            modules.forEach(element => {
                html += `<option value="${element.id}">
                    ${element.name}
                </option>`
            });

            $("#select-modules").html(html);
        }

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
                    url: '{{ route('student.learning_schedule.get_exams_schedule') }}',
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
                        </ul>
                        `,
                        container: 'body'
                    });
                },

                //trigger create exam modal
                dateClick: function(info) {
                    $("#create-exam-modal").modal('show');
                    $("#exam-date").val(info.dateStr);
                },
            });
            calendar.render();

            $("#new-exam-schedule").submit(function(event) {
                event.preventDefault();

                $('#create-exam-btn').prop('disabled', true);
                $('#create-exam-btn').html("<span role='btn-status'></span>Đang tạo");
                $("span[role='btn-status']").attr("class", "spinner-border spinner-border-sm mr-1");

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.exams.store') }}",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        calendar.refetchEvents();
                        $("#create-exam-modal").modal('hide');
                        //reset modal form
                        $("#new-exam-schedule > select").val(false).trigger('change');

                        $.toast({
                            heading: response.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            stack: false,
                        });

                        recreateSelectModules(response.data);
                    },
                    error: function(response) {
                        $.toast({
                            heading: "Không tạo được lịch thi",
                            showHideTransition: 'slide',
                            icon: 'error',
                            stack: false,
                        });
                    }
                });
            });
        });
    </script>
@endpush

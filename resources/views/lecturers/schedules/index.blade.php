@extends('lecturer_layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
@endpush
@section('content')
    <div id="calendar"></div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        $(document).ready(function() {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    center: 'dayGridMonth,timeGridWeek'
                },
                events: {
                    url: '{{ route('lecturer.schedule_teaching.getSchedules') }}',
                },
                initialView: 'dayGridMonth',
                selectable: true,
                // dateClick: function(info) {
                //     $("#datetime-booking").val(info.dateStr);
                //     $("#modal-booking").modal('show');
                // }
            });
            calendar.render();
        });
    </script>
@endpush

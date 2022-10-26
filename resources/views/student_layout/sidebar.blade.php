            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu left-side-menu-detached mm-active">
                <!--- Sidemenu -->
                <ul class="metismenu side-nav mm-show">

                    <li class="side-nav-item">
                        <a href="{{ route('student.index') }}" class="side-nav-link">
                            <i class="mdi mdi-calendar-month"></i>
                            <span> Xem lịch học </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.learning_schedule.history') }}" class="side-nav-link">
                            <i class="mdi mdi-history"></i>
                            <span> Xem lịch sử đi học </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.learning_schedule.exams_schedule') }}" class="side-nav-link">
                            <i class="mdi mdi-calendar-check"></i>
                            <span> Xem lịch thi </span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Left Sidebar End -->

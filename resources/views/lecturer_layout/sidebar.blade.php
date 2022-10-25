            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu left-side-menu-detached mm-active">
                <!--- Sidemenu -->
                <ul class="metismenu side-nav mm-show">

                    <li class="side-nav-item">
                        <a href="{{ route('lecturer.index') }}" class="side-nav-link">
                            <i class="mdi mdi-account-multiple-check"></i>
                            <span> Điểm danh </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('lecturer.schedule_teaching.index') }}" class="side-nav-link">
                            <i class="mdi mdi-calendar-month"></i>
                            <span> Xem lịch dạy </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('lecturer.exam_proctoring.index') }}" class="side-nav-link">
                            <i class="mdi mdi-calendar-check"></i>
                            <span> Xem lịch coi thi </span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Left Sidebar End -->

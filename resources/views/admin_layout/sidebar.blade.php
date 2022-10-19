            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu left-side-menu-detached mm-active">
                <!--- Sidemenu -->
                <ul class="metismenu side-nav mm-show">

                    <li class="side-nav-item">
                        <a href="{{ route('admin.users.index') }}" class="side-nav-link">
                            <i class="dripicons-user"></i>
                            <span> Quản lý người dùng </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('admin.students.index') }}" class="side-nav-link">
                            <i class="mdi mdi-school"></i>
                            <span> Quản lý sinh viên </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="apps-chat.html" class="side-nav-link">
                            <i class="mdi mdi-file-document-edit"></i>
                            <span> Quản lý điểm </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link" aria-expanded="true">
                            <i class="mdi mdi-folder-text"></i>
                            <span> Giảng dạy </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="side-nav-second-level mm-collapse" aria-expanded="false" style="">
                            <li class="side-nav-item">
                                <a href="{{ route('admin.modules.index') }}" aria-expanded="false">
                                    Phân công giảng dạy
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('admin.lecturers.index') }}" aria-expanded="false">
                                    Quản lý giảng viên
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('admin.exams.index') }}" class="side-nav-link">
                            <i class="mdi mdi-calendar-month"></i>
                            <span> Xếp lịch thi </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('admin.courses.index') }}" class="side-nav-link">
                            <i class="uil-invoice"></i>
                            <span> Quản lý khoá </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link" aria-expanded="true">
                            <i class="mdi mdi-folder-text"></i>
                            <span> Quản lý môn - chuyên ngành </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="side-nav-second-level mm-collapse" aria-expanded="false" style="height: 0px;">
                            <li>
                                <a href="{{ route('admin.subjects.index') }}">
                                    Quản lý môn
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.majors.index') }}">
                                    Quản lý chuyên ngành
                                </a>
                            </li>
                        </ul>
                    </li>
            </div>
            <!-- Left Sidebar End -->

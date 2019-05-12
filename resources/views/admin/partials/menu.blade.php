<!-- <li class="nav-item mT-30 active">
    <a class='sidebar-link' href="{{route('dashboard')}}" default>
        <span class="icon-holder">
            <i class="c-blue-500 ti-home"></i>
        </span>
        <span class="title">Dashboard</span>
    </a>
</li>
-->
<!-- ////////////////////////////////rta -->
@if($pageOnload->user->access_id > 11 && $pageOnload->user->access_id < 15) @if($pageOnload->user->access_id > 11 &&
    $pageOnload->user->access_id < 14) <li class="nav-item page-identifier" data-page="todays_activity">
        <a class='sidebar-link' href="/todays_activity">
            <span class="icon-holder">
                <i class="c-red-400 ti-stats-up"></i>
            </span>
            <span class="">Today</span>
        </a>
        </li>
        <li class="nav-item page-identifier" data-page="hrdashboard">
            <a class='sidebar-link' href="/dashboard">
                <span class="icon-holder">
                    <i class="c-red-400 ti-id-badge"></i>
                </span>
                <span class="">Employee</span>
            </a>
        </li>
        @else
        <li class="nav-item page-identifier" data-page="dashboard">
            <a class='sidebar-link' href="/dashboard">
                <span class="icon-holder">
                    <i class="c-red-400 ti-stats-up"></i>
                </span>
                <span class="">Today</span>
            </a>
        </li>
        @endif
        <li class="nav-item page-identifier" data-page="agent_schedules">
            <a class='sidebar-link' href="/agent_schedules">
                <!--  -->
                <span class="icon-holder">
                    <i class="c-red-400 ti-calendar"></i>
                </span>
                <span class="">Agent Schedules</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="incident_reports">
            <a class='sidebar-link' href="/incident_reports">
                <span class="icon-holder">
                    <i class="c-red-400 ti-write"></i>
                </span>
                <span class="">Incident Reports</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="work_reports">
            <a class='sidebar-link' href="/work_reports">
                <span class="icon-holder">
                    <i class="c-red-400 ti-clipboard"></i>
                </span>
                <span class="">Work Reports</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="leave_requests">
            <a class='sidebar-link' href="/leave_requests">
                <span class="icon-holder">
                    <i class="c-red-400 ti-agenda"></i>
                </span>
                <span class="">Event Requests</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="action_logs">
            <a class='sidebar-link' href="/action_logs">
                <span class="icon-holder">
                    <i class="c-red-400 ti-stamp"></i>
                </span>
                <span class="">Action Logs</span>
            </a>
        </li>
        @endif
        <!-- /////////////////////////////////////////////admin -->
        @if($pageOnload->user->access_id == 1)
        <li class="nav-item page-identifier" data-page="todays_activity">
            <a class='sidebar-link' href="/todays_activity">
                <span class="icon-holder">
                    <i class="c-red-400 ti-stats-up"></i>
                </span>
                <span class="">Today</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="hrdashboard">
            <a class='sidebar-link' href="/dashboard">
                <span class="icon-holder">
                    <i class="c-red-400 ti-id-badge"></i>
                </span>
                <span class="">Employee</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="agent_schedules">
            <a class='sidebar-link' href="/agent_schedules">
                <!--  -->
                <span class="icon-holder">
                    <i class="c-red-400 ti-calendar"></i>
                </span>
                <span class="">Agent Schedules</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="incident_reports">
            <a class='sidebar-link' href="/incident_reports">
                <span class="icon-holder">
                    <i class="c-red-400 ti-write"></i>
                </span>
                <span class="">Incident Reports</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="work_reports">
            <a class='sidebar-link' href="/work_reports">
                <span class="icon-holder">
                    <i class="c-red-400 ti-clipboard"></i>
                </span>
                <span class="">Work Reports</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="leave_requests">
            <a class='sidebar-link' href="/leave_requests">
                <span class="icon-holder">
                    <i class="c-red-400 ti-agenda"></i>
                </span>
                <span class="">Event Requests</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="action_logs">
            <a class='sidebar-link' href="/action_logs">
                <span class="icon-holder">
                    <i class="c-red-400 ti-stamp"></i>
                </span>
                <span class="">Action Logs</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="action_logs">
            <a class='sidebar-link' href="/agent">
                <span class="icon-holder">
                    <i class="c-red-400">A</i>
                </span>
                <span class="">Agent</span>
            </a>
        </li>
        @endif

        @if($pageOnload->user->access_id == 6)
        <li class="nav-item page-identifier" data-page="todays_activity">
            <a class='sidebar-link' href="/todays_activity">
                <span class="icon-holder">
                    <i class="c-red-400 ti-stats-up"></i>
                </span>
                <span class="">Today</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="tldashboard">
            <a class='sidebar-link' href="/tldashboard">
                <span class="icon-holder">
                    <i class="c-red-400">TL</i>
                </span>
                <span class="">Dashboard</span>
            </a>
        </li>
        <li class="nav-item page-identifier" data-page="incident_reports">
            <a class='sidebar-link' href="/incident_reports">
                <span class="icon-holder">
                    <i class="c-red-400 ti-write"></i>
                </span>
                <span class="">Incident Reports</span>
            </a>
        </li>
        @endif
        <!--///////////////////////////////// HRM and HRA -->
        @if($pageOnload->user->access_id > 1 && $pageOnload->user->access_id < 4) @if($pageOnload->user->access_id == 2)
            <li class="nav-item page-identifier" data-page="todays_activity">
                <a class='sidebar-link' href="/todays_activity">
                    <span class="icon-holder">
                        <i class="c-red-400 ti-stats-up"></i>
                    </span>
                    <span class="">Today</span>
                </a>
            </li>
            @endif

            <li class="nav-item page-identifier" data-page="hrdashboard">
                <a class='sidebar-link' href="/dashboard">
                    <span class="icon-holder">
                        <i class="c-red-400 ti-id-badge"></i>
                    </span>
                    <span class="">Employee</span>
                </a>
            </li>
            <li class="nav-item page-identifier" data-page="incident_reports">
                <a class='sidebar-link' href="/incident_reports">
                    <span class="icon-holder">
                        <i class="c-red-400 ti-write"></i>
                    </span>
                    <span class="">Incident Reports</span>
                </a>
            </li>
            <li class="nav-item page-identifier" data-page="action_logs">
                <a class='sidebar-link' href="/action_logs">
                    <span class="icon-holder">
                        <i class="c-red-400 ti-stamp"></i>
                    </span>
                    <span class="">Action Logs</span>
                </a>
            </li>
            @endif
            <!-- ////////////////////////////teamleader -->
            @if($pageOnload->user->access_id == 16)
            <li class="nav-item page-identifier" data-page="todays_activity">
                <a class='sidebar-link' href="/todays_activity">
                    <span class="icon-holder">
                        <i class="c-red-400 ti-stats-up"></i>
                    </span>
                    <span class="">Today</span>
                </a>
            </li>
            <li class="nav-item page-identifier" data-page="hrdashboard">
                <a class='sidebar-link' href="/dashboard">
                    <span class="icon-holder">
                        <i class="c-red-400 ti-id-badge"></i>
                    </span>
                    <span class="">Employee</span>
                </a>
            </li>
            <li class="nav-item page-identifier" data-page="incident_reports">
                <a class='sidebar-link' href="/incident_reports">
                    <span class="icon-holder">
                        <i class="c-red-400 ti-write"></i>
                    </span>
                    <span class="">Incident Reports</span>
                </a>
            </li>
            @endif
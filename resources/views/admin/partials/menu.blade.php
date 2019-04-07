<!-- <li class="nav-item mT-30 active">
    <a class='sidebar-link' href="{{route('dashboard')}}" default>
        <span class="icon-holder">
            <i class="c-blue-500 ti-home"></i>
        </span>
        <span class="title">Dashboard</span>
    </a>
</li>
-->
    <li class="nav-item">
        <a class='sidebar-link' href="/dashboard">
        <span class="icon-holder">
            <i class="c-brown-500 ti-dashboard"></i>
        </span>
        <span class="">Dashboard</span>
        </a>
    </li>

@if($pageOnload->user->access_id > 11 && $pageOnload->user->access_id < 15)
    <li class="nav-item">
        <a class='sidebar-link' href="/schedule">
        <span class="icon-holder">
            <i class="c-brown-500 ti-calendar"></i>
        </span>
        <span class="">Schedule</span>
        </a>
    </li>
    <li class="nav-item">
        <a class='sidebar-link' href="/rtareport">
        <span class="icon-holder">
            <i class="c-brown-500 ti-calendar"></i>
        </span>
        <span class="">Work Reports</span>
        </a>
    </li>
@endif

@if($pageOnload->user->access_id == 1)
    <li class="nav-item">
        <a class='sidebar-link' href="/rtadashboard">
        <span class="icon-holder">
            <i class="c-brown-500">RTA</i>
        </span>
        <span class="">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class='sidebar-link' href="/rtaschedule">
        <span class="icon-holder">
            <i class="c-brown-500">RTA</i>
        </span>
        <span class="">Schedule</span>
        </a>
    </li>
    <li class="nav-item">
        <a class='sidebar-link' href="/rtareport">
        <span class="icon-holder">
            <i class="c-brown-500">RTA</i>
        </span>
        <span class="">Work Reports</span>
        </a>
    </li>
    <li class="nav-item">
        <a class='sidebar-link' href="/tldashboard">
        <span class="icon-holder">
            <i class="c-brown-500">TL</i>
        </span>
        <span class="">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class='sidebar-link' href="/tlschedule">
        <span class="icon-holder">
            <i class="c-brown-500">TL</i>
        </span>
        <span class="">Work Reports</span>
        </a>
    </li>

@endif

@if($pageOnload->user->access_id == 6)
    <li class="nav-item">
        <a class='sidebar-link' href="/tldashboard">
        <span class="icon-holder">
            <i class="c-brown-500">TL</i>
        </span>
        <span class="">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class='sidebar-link' href="/tlschedule">
        <span class="icon-holder">
            <i class="c-brown-500">TL</i>
        </span>
        <span class="">Work Reports</span>
        </a>
    </li>
@endif
<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left">
            <li>
                <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
                    <i class="ti-menu"></i>
                </a>
            </li>

        </ul>
        <ul class="nav-right">
            <!-- <li class="notifications dropdown">
                <span class="counter bgc-red">3</span>
                <a href="" class="dropdown-toggle no-after" data-toggle="dropdown">
                    <i class="ti-list"></i>
                </a>
            </li> -->
            <sr-notif
                v-if="{{json_encode($access_id)}} == 1 || {{json_encode($access_id)}} == 12 || {{json_encode($access_id)}} == 13 || {{json_encode($access_id)}} == 14"
                v-bind:user-Id="{{json_encode($pageOnload->id)}}" v-bind:access-Id="{{json_encode($access_id)}}">
            </sr-notif>
            <ir-notif v-bind:user-Id="{{json_encode($pageOnload->id)}}" v-bind:access-Id="{{json_encode($access_id)}}">
            </ir-notif>
            <li class="dropdown">
                <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                    <div class="peer mR-10">
                        @if(!empty($pageOnload->image))
                        @php
                        echo '<div id="top-image-display" class="top-image-cover bdrs-50p"
                            style="background-image:url('.$pageOnload->image.')"></div>'
                        @endphp
                        @else
                        <div id="top-image-display" class="top-image-cover bdrs-50p"
                            style="background-image:url(/images/nobody.jpg)"></div>
                        @endif
                        <input type="hidden" id="logged-position" value="{{$pageOnload->user->access_id}}">
                        <input type="hidden" id="uid" value="{{$pageOnload->id}}">
                    </div>
                    <div class="peer">
                        <span id='top-bar-name' class="fsz-sm c-grey-900">{{$pageOnload->firstname }}
                            {{$pageOnload->middlename }} {{$pageOnload->lastname }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu fsz-sm">
                    <!-- <li>
                        <a href="" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-settings mR-10"></i>
                            <span>Setting</span>
                        </a>
                    </li> -->
                    <li>
                        <a id="loadProfilePreview" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-user mR-10"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-email mR-10"></i>
                            <span>Messages</span>
                        </a>
                    </li> -->
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="/logout" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-power-off mR-10"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>


    <!-- modal -->






</div>
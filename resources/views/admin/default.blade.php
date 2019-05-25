<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="images/icon.png" />


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">


    @yield('css')

</head>

<body class="app"
    style="{{isset($underconstruction)? 'background-image:url(/images/underconstruction.png);background-position:center;background-repeat:no-repeat;overflow-y:hidden':''}}">

    @include('admin.partials.spinner')

    <div id="app">
        <!-- #Left Sidebar ==================== -->
        @include('admin.partials.sidebar')

        <!-- #Main ============================ -->
        <div class="page-container" v-bind:style="page_busy?'cursor:progess':''">
            <!-- ### $Topbar ### -->
            @include('admin.partials.topbar')

            <!-- ### $App Screen Content ### -->
            @if(!isset($underconstruction))
            <main class='main-content bgc-grey-100'>
                <div id='mainContent'>
                    <div class="container-fluid">

                        <!-- <h4 class="c-grey-900 mT-10 mB-30">@yield('page-header')</h4> -->

                        @include('admin.partials.messages')
                        @yield('content')
                    </div>
                </div>
            </main>
            @endif
            <!-- ### $App Screen Footer ### -->
            <footer class="bdT  bgc-white  ta-c p-30 lh-0 fsz-sm c-grey-600">
                <span>Copyright © 2018 Designed by
                    <a href="https://colorlib.com" target='_blank' title="Colorlib">Colorlib</a> Powered by Solid Script
                    Web Systems. All rights reserved.</span>
            </footer>
        </div>
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>

    @yield('js')

</body>

</html>
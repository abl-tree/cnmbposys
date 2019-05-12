@extends('admin.default')

@section('content')

<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item w-100">
        <div class="bd bgc-white">
            {{-- <agent-widget v-bind:user-id="{{ json_encode($pageOnload->id) }}"></agent-widget> --}}
            <agent-widget v-bind:user-id="119"></agent-widget>
        </div>
    </div>
</div>

@endsection
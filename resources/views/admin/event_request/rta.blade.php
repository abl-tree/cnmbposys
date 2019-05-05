@extends('admin.default')

@section('content')

<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
        <request-schedule ></request-schedule>
    </div>
</div>
@endsection
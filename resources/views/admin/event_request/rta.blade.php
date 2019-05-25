@extends('admin.default')

@section('content')

<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item w-100">
        <request-schedule :user-id="{{json_encode($pageOnload->id)}}"></request-schedule>
    </div>
</div>

<profile-preview-modal v-bind:user-profile="{{json_encode($pageOnload->id)}}">
</profile-preview-modal>
<notifications group="foo" animation-type="velocity" position="bottom right"/>
@endsection
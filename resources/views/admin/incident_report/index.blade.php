@extends('admin.default')

@section('content')

<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item col-md-12">
        <incident-report v-bind:user-Id="{{json_encode($pageOnload->id)}}"></incident-report>
    </div>

    <!-- Modals -->

    <!-- profile preview modal -->
    <!-- <profile-preview-modal v-bind:user-profile="this.userId"></profile-preview-modal>
    <notifications group="foo" animation-type="velocity" position="bottom right"/> -->
</div>
@endsection
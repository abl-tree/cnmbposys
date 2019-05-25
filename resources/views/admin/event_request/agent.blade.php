@extends('admin.default')

@section('content')
<div class="row pos-r">
    <agent-tracker-only v-bind:user-id="{{ json_encode($pageOnload->id) }}"></agent-tracker-only>
    <div class="col-md-12 w-100">
        <agent-request-schedule :user-id="{{json_encode($pageOnload->id)}}"></agent-request-schedule>
    </div>
</div>

<profile-preview-modal v-bind:user-profile="{{json_encode($pageOnload->id)}}">
</profile-preview-modal>
<notifications group="foo" animation-type="velocity" position="bottom right"/>
@endsection
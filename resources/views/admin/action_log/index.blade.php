@extends('admin.default')

@section('content')
<!-- ### $App Screen Content ### -->
<action-log v-bind:user-Id="{{json_encode($pageOnload->id)}}" v-bind:access-Id="{{json_encode($access_id)}}">
</action-log>
<profile-preview-modal v-bind:user-profile="{{ json_encode($pageOnload->id) }}"></profile-preview-modal>
<notifications group="foo" animation-type="velocity" position="bottom right"/>
@endsection
@extends('admin.default')

@section('content')
<!-- ### $App Screen Content ### -->
<action-log v-bind:user-Id="{{json_encode($pageOnload->id)}}" v-bind:access-Id="{{json_encode($access_id)}}">
</action-log>
@endsection
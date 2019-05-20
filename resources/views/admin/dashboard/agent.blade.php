@extends('admin.default')

@section('content')

<div class="row fluid-container pos-r">
    <agent-tracker-only v-bind:user-id="{{json_encode($pageOnload->id)}}"></agent-tracker-only>
    <div class="col-md-12">
        <stats-component-2 v-bind:user-Id="{{json_encode($pageOnload->id)}}"
            v-bind:access-Id="{{json_encode($access_id)}}"></stats-component-2>
    </div>
    <div class="col-md-12 mT-20">
        <agent-cluster v-bind:user-Id="{{json_encode($pageOnload->id)}}"
            v-bind:access-Id="{{json_encode($access_id)}}"></agent-cluster>
    </div>
  <div>

@endsection
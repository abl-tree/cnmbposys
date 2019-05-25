@extends('admin.default')

@section('content')

  <div class="row pos-r">
    <agent-work-report v-bind:user-id="{{ json_encode($pageOnload->id) }}"></agent-work-report>
  <div>

  <profile-preview-modal v-bind:user-profile="{{json_encode($pageOnload->id)}}">
  </profile-preview-modal>
  <notifications group="foo" animation-type="velocity" position="bottom right"/>
@endsection
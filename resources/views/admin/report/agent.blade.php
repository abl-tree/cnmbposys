@extends('admin.default')

@section('content')

  <div class="row pos-r">
    <agent-work-report v-bind:user-id="{{ json_encode($pageOnload->id) }}"></agent-work-report>
  <div>

@endsection
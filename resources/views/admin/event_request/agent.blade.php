@extends('admin.default')

@section('content')
  <div class="row pos-r">
    <agent-tracker-only v-bind:user-id="{{ json_encode($pageOnload->id) }}"></agent-tracker-only>
    <div class="col-md-12 w-100">
        <agent-request-schedule :user-id="{{json_encode($pageOnload->id)}}"></agent-request-schedule>
    </div>
</div>
@endsection
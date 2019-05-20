@extends('admin.default')

@section('content')
  <div class="row  pos-r">
    <agent-tracker-only v-bind:user-id="{{ json_encode($pageOnload->id) }}"></agent-tracker-only>
    <div class="col-md-12">
        <incident-report v-bind:user-Id="{{ json_encode($pageOnload->id) }}"
            v-bind:access-Id="{{json_encode($access_id)}}"></incident-report>
    </div>
  </div>
@endsection
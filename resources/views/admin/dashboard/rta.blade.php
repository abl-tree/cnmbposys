@extends('admin.default')

@section('content')

   <rta-dashboard-section v-bind:user-Id="{{json_encode($pageOnload->id)}}"></rta-dashboard-section>
@endsection
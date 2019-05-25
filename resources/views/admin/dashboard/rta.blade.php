@extends('admin.default')

@section('content')

   <rta-dashboard-section v-bind:user-Id="{{json_encode($pageOnload->id)}}"></rta-dashboard-section>
   
   <profile-preview-modal v-bind:user-profile="{{json_encode($pageOnload->id)}}">
   </profile-preview-modal>
   <notifications group="foo" animation-type="velocity" position="bottom right"/>
@endsection
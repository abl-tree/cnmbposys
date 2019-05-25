@extends('admin.default')

@section('content')
<!-- ### $App Screen Content ### --> 
   <rta-sched-section v-bind:user-Id="{{ json_encode($pageOnload->id) }}"></rta-sched-section>
   <profile-preview-modal v-bind:user-profile="{{ json_encode($pageOnload->id) }}"></profile-preview-modal>
   <notifications group="foo" animation-type="velocity" position="bottom right"/>
@endsection

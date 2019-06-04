@extends('admin.default')

@section('content')
    <employee-form :form="{{ json_encode($form) }}" :accessId="{{ json_encode($accessId) }}" :action="{{ json_encode($action) }}"></employee-form>
@endsection
@extends('admin.default')

@section('content')

<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
        <employee-table></employee-table>
   </div>
</div>
{{-- @include('admin.dashboard.include.employee_form_modal'); --}}
@endsection
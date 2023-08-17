@extends('master')
@section('title')
    <h1>Quản lý ảnh</h1>
@endsection
@section('content')
    @include('page.ql_anh')
@endsection
@section('js')
<script src="/project/ql_anh.js"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    var route_prefix = "laravel-filemanager";
    $('#lfm').filemanager('image', {prefix: route_prefix});
    $('#lfm_edit').filemanager('image', {prefix: route_prefix});
</script>

@endsection

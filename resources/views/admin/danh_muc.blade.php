@extends('master')
@section('title')
    <H1><b>Danh Mục Sản Phẩm</b></H1>
@endsection
@section('content')
    @include('page.danh_muc')
@endsection

@section('js')

    <script src="/project/quan_ly_danh_muc.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        var route_prefix = "laravel-filemanager";
        $('#lfm').filemanager('image', {prefix: route_prefix});
        $('#lfm_edit').filemanager('image', {prefix: route_prefix});
    </script>
@endsection

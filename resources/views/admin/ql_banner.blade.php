@extends('master')
@section('title')
    <H1><b>Quản Lý Banner</b></H1>
@endsection
@section('content')
    @include('page.banner')
@endsection
@section('js')
    <script src="/project/quan_ly_banner.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        var route_prefix = "laravel-filemanager";
        $('.lfm').filemanager('image', {prefix: route_prefix});
        $('#lfm_edit').filemanager('image', {prefix: route_prefix});
    </script>
@endsection

@extends('nhan_vien.master')
@section('title')
    <h1>Quản lý ảnh</h1>
@endsection
@section('content')
    @include('page.ql_anh')
@endsection
@section('js')
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
    <script>
        $('.lfm').filemanager('image');
    </script>
   <script src="/project/ql_anh.js"></script>
    <script>
        var route_prefix = "/laravel-filemanager";
    </script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('.lfm').filemanager('image', {
            prefix: '/laravel-filemanager'
        });
        $('.edit_lfm').filemanager('image', {
            prefix: '/laravel-filemanager'
        });
    </script>
@endsection

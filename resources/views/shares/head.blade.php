<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AdminLTE 3 | Dashboard</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="/dist/css/adminlte.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{URL::to('owlcarousel/assets/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{URL::to('owlcarousel/assets/owl.theme.default.min.css')}}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .layout-fixed .main-sidebar {
        bottom: 0;
        float: none;
        left: 0;
        position: fixed;
        top: 0;
        overflow: scroll;
    }
</style>

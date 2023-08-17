@extends('master')
@section('title')
    <h1>Thống Kê</h1>
@endsection
@section('content')
    @include('page.thong_ke')
@endsection
@section('js')
    <script src="/project/thong_ke.js"></script>
    <script src="{{URL::to('owlcarousel/owl.carousel.min.js')}}"></script>
    <script>
        // $(document).ready(function() {
        //     $(".owl-carousel").owlCarousel({
        //         margin: 10,
        //         nav: true,
        //         navText: ["«", "»"],
        //         loop: true,
        //         dots: false,
        //         responsive: {
        //             1000: {
        //                 items: 3.5,
        //                 merge: true,
        //             }
        //         }

        //     });
        // });
        $('.owl-carousel').owlCarousel({
            loop: false,
            margin: 5,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: false
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: false
                }
            }
        })
    </script>

    <script>
        $(document).ready(function() {
            function Danhthu() {
                $.ajax({
                    url: '/admin/thong-ke/data',
                    type: 'get',
                    success: function(res) {
                        console.log(res)
                        const data = {
                            labels: ['January', 'February', 'March', 'April', 'May', 'Jun', 'July',
                                'August', 'September',
                                'October',
                                'November', 'December'
                            ],
                            datasets: [{
                                label: '    ',
                                data: res.data,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(201, 203, 207, 0.2)'
                                ],
                                borderColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(255, 159, 64)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(54, 162, 235)',
                                    'rgb(153, 102, 255)',
                                    'rgb(201, 203, 207)'
                                ],
                                borderWidth: 1
                            }],
                        };
                        const config = {
                            type: 'bar',
                            data: data,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            },
                        };
                        const ctx = document.getElementById('myChart');
                        new Chart(ctx, config);
                    },
                });
            }


            function Product() {
                $.ajax({
                    url: '/admin/thong-ke/product',
                    type: 'get',
                    success: function(res) {
                        console.log(res.product)
                        const data = {
                            labels: res.product,
                            datasets: [{
                                label: 'My First Dataset',
                                data: res.tyle,
                                backgroundColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(54, 162, 235)',
                                    'rgb(255, 205, 86)',
                                    'rgb(0,255,255)',
                                ],
                                hoverOffset: 4
                            }]
                        };
                        const config = {
                            type: 'doughnut',
                            data: data,
                        };
                        const ctx = document.getElementById('doughnutSanPham');
                        new Chart(ctx, config);
                    }
                });
            }

            function Customer() {
                $.ajax({
                    url: '/admin/thong-ke/customer',
                    type: 'get',
                    success: function(res) {
                        console.log(res.email)
                        const data = {
                            labels: res.email,
                            datasets: [{
                                label: 'My First Dataset',
                                data: res.tyle,
                                backgroundColor: [
                                    'rgb(255, 99, 132)',
                                    'rgb(54, 162, 235)',
                                    'rgb(255, 205, 86)',
                                    'rgb(0,255,255)',
                                ],
                                hoverOffset: 4
                            }],
                        };
                        const config = {
                            type: 'doughnut',
                            data: data,
                        };
                        const ctx = document.getElementById('doughnutCustomer');
                        new Chart(ctx, config);
                    }
                });
            }
            Danhthu();
            Product();
            Customer();
        });
    </script>
    <script src="/plugins/chart.js/chart.min.js"></script>

    <script src="/project/thong_ke.js"></script>
@endsection

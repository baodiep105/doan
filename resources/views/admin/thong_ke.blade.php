@extends('master')
@section('title')
    <h1>Thống Kê</h1>
@endsection
@section('content')
    @include('page.thong_ke')
@endsection
@section('js')
    <script src="/project/thong_ke.js"></script>
    <script>
        $(document).ready(function() {
            $(".owl-carousel").owlCarousel({
                margin: 10,
                nav: true,
                navText: ["«", "»"],
                loop: true,
                dots: false,
                responsive: {
                    1000: {
                        items: 3.5,
                        merge: true,
                    }
                }

            });
        });
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

            // const data = {
            //     labels: res.data.product,
            //     datasets: [{
            //         label: 'My First Dataset',
            //         data: res.data.tyle,
            //         backgroundColor: [
            //             'rgb(255, 99, 132)',
            //             'rgb(54, 162, 235)',
            //             'rgb(255, 205, 86)'
            //         ],
            //         hoverOffset: 4
            //     }]
            // };
            // const config = {
            //     type: 'doughnut',
            //     data: data,
            // };
            // const ctx = document.getElementById('doughnutSanPham');
            // new Chart(ctx, config);

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
    <script src="chart.min.js"></script>
    <script>
        //     new Vue({
        //         el: '#app',
        //         data: {
        //             doanh_thu: '',
        //             san_pham_ban_chay: [],
        //             khuyenmai: '',
        //             khach_hang_mua_nhieu: [],

        //         },
        //         created() {
        //             this.loadData();
        //         },
        //         methods: {
        //             loadData() {
        //                 const elementDoanhThu = document.querySelector('.doanh_thu');

        //                 axios
        //                     .get('/admin/thong-ke/all')
        //                     .then((res) => {
        //                         this.doanh_thu = res.data.doanh_thu;
        //                         elementDoanhThu.innerText = res.data.doanh_thu;
        //                         this.san_pham_ban_chay = res.data.san_pham_ban_chay;
        //                         this.khuyenmai = res.data.sl_san_pham_khuyen_mai;
        //                         this.khach_hang_mua_nhieu = res.data.khach_hang_mua_nhieu;
        //                         // console.log(this.doanh_thu);
        //                         console.log(elementDoanhThu);
        //                     });
        //             },
        //             create() {
        //                 axios
        //                     .post('/admin/san-pham/create', this.sanPhamCreate)
        //                     .then((res) => {
        //                         toastr.success("Đã thêm mới sản phẩm thành công!!!");
        //                         this.loadData();
        //                     })
        //                     .catch((res) => {
        //                         var danh_sach_loi = res.response.data.errors;
        //                         $.each(danh_sach_loi, function(key, value) {
        //                             toastr.error(value[0]);
        //                         });
        //                     });
        //             },
        //         },
        //     });
        //
    </script>

    <script src="/project/thong_ke.js"></script>
@endsection

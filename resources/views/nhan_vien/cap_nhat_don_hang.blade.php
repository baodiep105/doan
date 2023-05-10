@extends('nhan_vien.master')
@section('title')
<h1>Cập nhật đơn hàng</h1>
@endsection
@section('content')
<div id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="card-title">Danh sách đơn hàng</h4>
                        </div>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="row mt-2 ml-2">
                        <div class="col-md-4">
                            <div class="input-group mb-2 ">
                                <input type="text" v-model="inputSearch" class="form-control" placeholder="search"
                                    aria-label="Nhập danh mục cần tìm" aria-describedby="button-addon2">
                                <button v-on:click="search()"class="btn btn-outline-secondary" type="button"
                                    id="button-addon2">search</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Tổng Tiền</th>
                                    <th>Tiền Giảm Giá</th>
                                    <th>Thực Trả</th>
                                    <th>Người Nhận</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Địa Chỉ</th>
                                    <th>Ngày Tạo</th>
                                    <th style="width:170px">Trạng Thái</th>
                                    <th>Chi Tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value, key) in list_vue">
                                    <th class="text-center align-middle">@{{ key + 1 }}</th>
                                    <td class="text-center align-middle">@{{ value.email }}</td>
                                    <td class="text-center align-middle">@{{ value.tong_tien }}</td>
                                    <td class="align-middle">@{{ value.tien_giam_gia }}</td>
                                    <td class="text-center align-middle">@{{ value.thuc_tra }}</td>
                                    <td class="text-center align-middle">@{{ value.nguoi_nhan }}</td>
                                    <td class="text-center align-middle">@{{ value.sdt }}</td>
                                    <td class="text-center align-middle">@{{ value.dia_chi }}</td>
                                    <td class="text-center align-middle">@{{ value.created_at }}</td>
                                    <td>
                                        <template>
                                            <div class="input-group mb-3">
                                                <template v-if="value.status==0">
                                                    <input  type="text" value='Đã hủy' class="form-control">
                                                </template>
                                                <template v-else-if="value.status==1">
                                                    <input  type="text" value='Đã thanh toán'class="form-control"
                                                        aria-label="Text input with dropdown button">
                                                </template>
                                                <template v-else>
                                                    <input  type="text" value='Đang giao 'class="form-control"
                                                        aria-label="Text input with dropdown button">
                                                </template>
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-expanded="false"></button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item"v-on:click="doiTrangThai(value.id,2)">Đang giao</a></li>
                                                    <li><a class="dropdown-item" v-on:click="doiTrangThai(value.id,1)" >Đã thanh toán</a></li>
                                                    <li><a class="dropdown-item" v-on:click="doiTrangThai(value.id,0)">Đã hủy</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="text-center">
                                        <a v-bind:href="'/admin/quan-ly-don-hang/chi-tiet/'+value.id"><button
                                                class="btn btn-primary">detail </button></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav style="margin-top: 3px" aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item" >
                                    <a class="page-link"  v-on:click="fetchDonHang(pagination.prev_page_url)" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Trang trước</span>
                                    </a>
                                </li>
                                <template v-for="(value, key) in url">
                                        {{-- <template v-if="key!=0 && key!=index">
                                        </template> --}}
                                    <li v-if="key!=0 && key!=index" class="page-item">
                                        <a  class="page-link" v-on:click="fetchDonHang(value.url)">@{{value.label}}</a>
                                    </li>
                                </template>

                                <li class="page-item" >

                                    <a class="page-link"  v-on:click="fetchDonHang(pagination.next_page_url)" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Trang sau</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
<script>
    $('.lfm').filemanager('image');
</script> --}}
<script src="/project/quan_ly_don_hang.js"></script>
@endsection


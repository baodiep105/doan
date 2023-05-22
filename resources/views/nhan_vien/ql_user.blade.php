@extends('nhan_vien.master')
@section('title')
      <h1>Quản Lý Khách hàng</h1>
@endsection
@section('content')
<div id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <h4 class="card-title">Danh sách khách hàng</h4>
                        </div>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="row mt-2 ml-2 mb-2">
                        <div class="col-md-4">
                            <div class="input-group ">
                                <input type="text" v-model="inputSearch" class="form-control" placeholder="search"
                                    aria-label="Nhập danh mục cần tìm" aria-describedby="button-addon2">
                                <button v-on:click="search()"class="btn btn-outline-secondary" type="button"
                                    id="button-addon2">search</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered mb-0" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>User name </th>
                                <th style="width: 5px;">Email</th>
                                {{-- <th>Họ Và tên</th> --}}
                                <th>Số Điện Thoại</th>
                                <th>Địa Chỉ</th>
                                <th>Ngày Tạo</th>
                                <th>Tình Trạng</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, key) in list_vue">
                                <th class="text-center align-middle">@{{ key + 1 }}</th>
                                <td class="text-center align-middle">@{{ value.username }}</td>
                                <td class="text-center align-middle" >@{{value.email}}</td>
                                {{-- <td class="align-middle">@{{ value.ho_lot }} @{{ value.ten }}</td> --}}
                                <td class="text-center align-middle">@{{ value.sdt }}</td>
                                <td class="text-center align-middle" style="width: 0em" >@{{ value.dia_chi }} </td>
                                <td class="text-center align-middle">@{{ value.created_at }}</td>
                                <td>
                                    <template v-if="value.is_block==1">
                                        <button v-on:click="doiTrangThai(value.id)" class="btn btn-primary">hiển
                                            thị</button>
                                    </template>
                                    <template v-else>
                                        <button v-on:click="doiTrangThai(value.id)" class="btn btn-danger">tạm
                                            tắt</button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav style="margin-top: 3px" aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item" >
                                <a class="page-link"  v-on:click="fetchUser(pagination.prev_page_url)" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Trang trước</span>
                                </a>
                            </li>
                            <template v-for="(value, key) in url">
                                    {{-- <template v-if="key!=0 && key!=index">
                                    </template> --}}
                                <li v-if="key!=0 && key!=index" class="page-item">
                                    <a  class="page-link" v-on:click="fetchUser(value.url)">@{{value.label}}</a>
                                </li>
                            </template>

                            <li class="page-item" >

                                <a class="page-link"  v-on:click="fetchUser(pagination.next_page_url)" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Trang sau</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    {{-- <div class="table">
                    </div> --}}
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
<script src="/project/quan_ly_user.js"></script>
@endsection

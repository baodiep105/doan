<div id='app'>
    <div class="container-fluid">
        <div class="card-content collapse show">
            <div class="table-responsive">
                <div class="row mt-2 ml-2">
                    <div class="col-md-4">
                        <div class="input-group mb-2 ">
                            <input v-on:keyup.enter="search()"type="text" v-model="inputSearch" class="form-control"
                                placeholder="search" aria-label="Nhập danh mục cần tìm"
                                aria-describedby="button-addon2">
                            <button v-on:click="search()"class="btn btn-outline-secondary" type="button"
                                id="button-addon2">search</button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row justify-content-end">
                            <a v-bind:href="'/create-san-pham'"><button class="btn btn-primary mr-4"
                                    type="submit">Thêm</button>
                            </a>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered mb-0" id="tableSanPham">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center">#</th>
                            <th class="text-nowrap text-center">Tên Sản Phẩm</th>
                            <th class="text-nowrap text-center">Danh mục</th>
                            <th class="text-nowrap text-center">Thương hiệu</th>
                            <th class="text-nowrap text-center">Giá Bán</th>
                            <th class="text-nowrap text-center">Giá Khuyến Mãi</th>
                            <th class="text-nowrap text-center">Tình Trạng</th>
                            <th class="text-nowrap text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(value, key) in danhSachSanPham">
                            <tr>
                                <th class="text-nowrap text-center">@{{ key + 1 }}</th>
                                <td>@{{ value.ten_san_pham }}</td>
                                <td>@{{ value.ten_danh_muc }}</td>
                                <td>@{{ value.brand }}</td>
                                <td>@{{ value.gia_ban }}</td>
                                <td>@{{ value.gia_khuyen_mai }}</td>
                                <td>
                                    <template v-if="value.is_open">
                                        <button v-on:click="changeStatus(value.id)" class="btn btn-primary">Hiển
                                            Thị</button>
                                    </template>
                                    <template v-else>
                                        <button v-on:click="changeStatus(value.id)" class="btn btn-danger">Tạm
                                            Tắt</button>
                                    </template>
                                    {{-- @{{ value.is_open == 1 ? "Hiển Thị" : "" }} --}}
                                </td>
                                <td class="text-center">
                                    {{-- <a href=""><i class="fa-sharp fa-solid fa-pencil"></i></a> --}}
                                    <a href="" data-toggle="modal" data-target="#editModal"
                                        v-on:click="editSanPham(value.id)"><i class="fas fa-edit"></i></a>
                                    <a href="" data-toggle="modal" data-target="#deleteModal"
                                        v-on:click="deleteSanPham(value.id)"><i class="far fa-trash-alt"></i></a>
                                    {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#editModal" v-on:click="editSanPham(value.id)">Edit</button> --}}
                                    {{-- <button class="btn"  data-toggle="modal" data-target="#deleteModal" v-on:click="deleteSanPham(value.id)"><i class="fa-solid fa-trash-can"></i></button> --}}
                                </td>
                            </tr>

                        </template>
                    </tbody>
                </table>
                 <nav style="margin-top: 3px" aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" >
                            <a class="page-link"  v-on:click="fetchCustomers(pagination.prev_page_url)" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Trang trước</span>
                            </a>
                        </li>
                        <template v-for="(value, key) in url">
                                {{-- <template v-if="key!=0 && key!=index">
                                </template> --}}
                            <li v-if="key!=0 && key!=index" class="page-item">
                                <a  class="page-link" v-on:click="fetchCustomers(value.url)">@{{value.label}}</a>
                            </li>
                        </template>

                        <li class="page-item" >

                            <a class="page-link"  v-on:click="fetchCustomers(pagination.next_page_url)" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Trang sau</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xóa Sản Phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" v-model="id_delete" placeholder="Nhập vào id cần xóa"
                            hidden>
                        Bạn có chắc chắn muốn xóa? Điều này không thể hoàn tác.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                            v-on:click="acceptDelete()">Xóa </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Sản Phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" v-model="idEdit" hidden>
                        <div class="position-relative form-group">
                            <label>Tên sản phẩm</label>
                            <input v-model='ten_san_pham_edit' type="text" class="form-control"
                                placeholder="Nhập vào ten sản phẩm">
                        </div>
                        <div class="position-relative form-group">
                            <label>Thương hiệu</label>
                            <input v-model='brand_edit'type="text" class="form-control"
                                placeholder="Nhập vào Thương hiệu">
                        </div>
                        <div class="position-relative form-group">
                            <label>Giá Bán</label>
                            <input v-model='gia_ban_edit' type="number" class="form-control"
                                placeholder="Nhập vào giá bán">
                        </div>
                        <div class="position-relative form-group">
                            <label for="placeTextarea">Mô Tả Ngắn</label>
                            <textarea v-model='mo_ta_ngan_edit'class="form-control" cols="30" rows="5"
                                placeholder="Nhập vào mô tả ngắn"></textarea>
                        </div>
                        <div class="position-relative form-group">
                            <label>Mô Tả Chi Tiết</label>
                            <textarea v-model='mo_ta_chi_tiet_edit'class="form-control" cols="30" rows="5"
                                placeholder="Nhập vào mô tả chi tiết"></textarea>
                        </div>
                        <div class="position-relative form-group">
                            <label>Danh Mục</label>
                            <select v-model='id_danh_muc_edit' class="custom-select block">
                                <template v-for="(value, key) in danhSachDanhMuc">
                                    <option v-bind:value="value.id">@{{ value.ten_danh_muc }}</option>
                                </template>
                            </select>
                        </div>

                        <div class="position-relative form-group">
                            <label>Trạng thái</label>
                            <select v-model="trang_thai_edit" class="form-control">
                                <option value=1>Hiển Thị</option>
                                <option value=0>Tạm Tắt</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal"
                            v-on:click="acceptUpdate()">Cập Nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

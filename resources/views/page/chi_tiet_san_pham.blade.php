<div id="app">
    <div class="row justify-content-end mt-2 ml-2">
        <div class="col-md-5">
            <div class="input-group mb-2 ">
                <input type="text" v-model="inputSearch" class="form-control" placeholder="search"
                    aria-label="Nhập danh mục cần tìm" aria-describedby="button-addon2">
                <button v-on:click="search()"class="btn btn-outline-secondary" type="button"
                    id="button-addon2">search</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Thêm mới chi tiết sản phẩm</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="issueinput6">Chọn sản phẩm</label>
                                        <select v-model="id_sanpham" id="issueinput6" class="form-control"
                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                            data-title="Status" data-original-title="" title="">
                                            <template v-for="(value, key) in danh_sach_san_pham">
                                                <option v-bind:value="value.id">@{{ value.ten_san_pham }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="issueinput6">Chọn màu sắc</label>
                                        <select v-model="id_mau" id="issueinput6" class="form-control"
                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                            data-title="Status" data-original-title="" title="">
                                            <template v-for="(value, key) in danh_sach_mau">
                                                <option v-bind:value="value.id">@{{ value.ten_mau }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="issueinput6">Chọn size</label>
                                        <select v-model="id_size" id="issueinput6" name="status" class="form-control"
                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                            data-title="Status" data-original-title="" title="">
                                            <template v-for="(value, key) in danh_sach_size">
                                                <option v-bind:value="value.id">@{{ value.size }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Số lượng</label>
                                        <input v-model="sl" type="number" class="form-control"
                                            placeholder="Nhập vào ten sản phẩm">
                                    </div>
                                </div>

                                <div class="position-relative form-group">
                                    <label>Tình Trạng</label>
                                    <select v-model="trang_thai" class="form-control">
                                        <option value=1 selected>Hiển Thị</option>
                                        <option value=0>Tạm Tắt</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-actions right">
                                <button type="button" v-on:click="create($event)" class="btn btn-primary">Thêm
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="card-title">Danh sách chi tiết sản phẩm</p>
                                </div>
                            </div>

                        </div>
                        <div class="card-content collapse show">

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Màu sắc</th>
                                            <th>size</th>
                                            <th>Số lượng</th>
                                            <th>Trạng thái</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(value, key) in danh_sach_chi_tiet">
                                            <th class="text-center align-middle">@{{ key + 1 }}</th>
                                            <td class="align-middle">@{{ value.ten_san_pham }}</td>
                                            <td class="text-center align-middle">@{{ value.ten_mau }}</td>
                                            <td class="text-center align-middle">@{{ value.size }}</td>
                                            <td class="text-center align-middle">@{{ value.sl_chi_tiet }}</td>
                                            <td>
                                                <template v-if="value.status==1">
                                                    <button v-on:click="doiTrangThai(value.id)"
                                                        class="btn btn-primary">hiển thị</button>
                                                </template>
                                                <template v-else>
                                                    <button v-on:click="doiTrangThai(value.id)"
                                                        class="btn btn-danger">tạm tắt</button>
                                                </template>
                                            </td>
                                            <td class="text-center">
                                                <a href="" data-toggle="modal" data-target="#editModal"
                                                    v-on:click="editDanhMuc(value.id)"><i class="fas fa-edit"></i></a>
                                                <a href=""data-toggle="modal" data-target="#deleteModal"
                                                    v-on:click="deleteDanhMuc(value.id)"><i
                                                        class="far fa-trash-alt"></i></a>
                                                {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#editModal" v-on:click="editDanhMuc(value.id)">Edit</button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" v-on:click="deleteDanhMuc(value.id)">Delete</button> --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <nav style="margin-top: 5px" aria-label="Page navigation example">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa chi tiết sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" v-model="idDelete" placeholder="Nhập vào id cần xóa"
                        hidden>
                    Bạn có chắc chắn muốn xóa? Điều này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" v-on:click="acceptDelete()"
                        data-dismiss="modal">Xóa chi tiết sản phẩm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh Sửa Chi Tiết Sản Phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" v-model="idEdit" >
                    <div class="position-relative form-group">
                        <label>Tên sản phẩm</label>
                        <select v-model="id_sanpham_edit" id="issueinput6" class="form-control"
                            data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Status"
                            data-original-title="" title="">
                            <template v-for="(value, key) in danh_sach_san_pham">
                                <option v-bind:value="value.id">@{{ value.ten_san_pham }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="position-relative form-group">
                        <label>Màu sắc</label>
                        <select v-model="id_mau_edit" id="issueinput6" class="form-control" data-toggle="tooltip"
                            data-trigger="hover" data-placement="top" data-title="Status" data-original-title=""
                            title="">
                            <template v-for="(value, key) in danh_sach_mau">
                                <option v-bind:value="value.id">@{{ value.ten_mau }}</option>
                            </template>
                        </select>
                    </div>

                    <div class="position-relative form-group">
                        <label>size</label>
                        <select v-model="id_size_edit" id="issueinput6" name="status" class="form-control"
                            data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Status"
                            data-original-title="" title="">
                            <template v-for="(value, key) in danh_sach_size">
                                <option v-bind:value="value.id">@{{ value.size }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="position-relative form-group">
                        <label>Số lượng</label>
                        <input v-model="sl_edit" type="number" class="form-control"
                            placeholder="Nhập vào ten sản phẩm">
                    </div>
                    <div class="position-relative form-group">
                        <label>size</label>
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

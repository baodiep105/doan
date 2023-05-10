<div id="app">
    {{-- <div class="row justify-content-end mb-2">
        <div class="col-md-5">
            <div class="input-group ">
                <input type="text" v-model="inputSearch" class="form-control" placeholder="search"
                    aria-label="Nhập danh mục cần tìm" aria-describedby="button-addon2">
                <button v-on:click="search()"class="btn btn-outline-secondary" type="button"
                    id="button-addon2">search</button>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Quản lý Khuyến Mãi</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="issueinput6">Tên sản phẩm</label>
                                        <select id="issueinput6" v-model="add.id_san_pham" name="status"
                                            class="form-control" data-toggle="tooltip" data-trigger="hover"
                                            data-placement="top" data-title="Status" data-original-title=""
                                            title="">
                                            <template v-for="(value, key) in ds_san_pham">
                                                <option v-bind:value="value.id">@{{ value.ten_san_pham }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">Tỷ lệ khuyến mãi</label>
                                            <input type="number" v-model='add.ty_le'class="form-control"
                                                v-model="re_password" name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="issueinput6">Tình Trạng</label>
                                        <select v-model="add.is_open" name="status" class="form-control">
                                            <option value="1">Hiển Thị</option>
                                            <option value="0">Tạm Tắt</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <button type="button" v-on:click="create($event)" class="btn btn-primary">Thêm Khuyến
                                    mãi
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
                                <div class="col-md-4">
                                    <h4 class="card-title">Danh sách nhan vien</h4>
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
                                            <th>tỷ lệ khuyến mãi</th>
                                            <th>Ngày tạo</th>
                                            <th>Tình trạng</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(value, key) in list_vue">
                                            <th class="text-center align-middle">@{{ key + 1 }}</th>
                                            <td class="align-middle">@{{ value.ten_san_pham }}</td>
                                            <td class="align-middle text-center">@{{ value.ty_le }}%</td>
                                            <td class="align-middle text-center">@{{ value.created_at }}</td>
                                            <td>
                                                <template v-if="value.is_open==1">
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
                                                <a href=""data-toggle="modal" data-toggle="modal"
                                                    data-target="#deleteModal" v-on:click="deletekhuyenmai(value.id)"><i
                                                        class="far fa-trash-alt"></i></a>
                                                {{-- <button class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModal"
                                                    v-on:click="editDanhMuc(value.id)">Edit</button>
                                                <button class="btn btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    v-on:click="deletekhuyenmai(value.id)">Delete</button> --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <nav style="margin-top: 3px" aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item" >
                                            <a class="page-link"  v-on:click="fetchKhuyenMai(pagination.prev_page_url)" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Trang trước</span>
                                            </a>
                                        </li>
                                        <template v-for="(value, key) in url">
                                                {{-- <template v-if="key!=0 && key!=index">
                                                </template> --}}
                                            <li v-if="key!=0 && key!=index" class="page-item">
                                                <a  class="page-link" v-on:click="fetchKhuyenMai(value.url)">@{{value.label}}</a>
                                            </li>
                                        </template>

                                        <li class="page-item" >

                                            <a class="page-link"  v-on:click="fetchKhuyenMai(pagination.next_page_url)" aria-label="Next">
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
                    <h5 class="modal-title">Xóa tài khoản</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" v-model="idDelete" placeholder="Nhập vào id cần xóa" hidden>
                    Bạn có chắc chắn muốn xóa? Điều này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" v-on:click="acceptDelete()"
                        data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa nhân viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="position-relative form-group">
                        <input type="text" v-model="update.idEdit" hidden>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="issueinput6">Tên sản phẩm</label>
                                <select id="issueinput6" v-model="update.id_san_pham_edit" name="status"
                                    class="form-control" data-toggle="tooltip" data-trigger="hover"
                                    data-placement="top" data-title="Status" data-original-title="" title="">
                                    <template v-for="(value, key) in ds_san_pham">
                                        <option v-bind:value="value.id">@{{ value.ten_san_pham }}</option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="userinput1">Tỷ lệ khuyến mãi</label>
                                    <input type="number" v-model='update.ty_le_edit'class="form-control"
                                        v-model="re_password" name="ten_danh_muc">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="issueinput6">Tình Trạng</label>
                                <select v-model="update.is_open_edit" name="status" class="form-control">
                                    <option value="1">Hiển Thị</option>
                                    <option value="0">Tạm Tắt</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                        v-on:click="acceptUpdate()">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

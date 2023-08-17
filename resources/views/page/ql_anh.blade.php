<div id="app">
    <div class="row justify-content-end mb-2">
        <div class="col-md-5">
            <div class="input-group ">
                <input type="text" v-model="inputSearch" class="form-control" placeholder="search"
                    aria-label="Nhập danh mục cần tìm" aria-describedby="button-addon2">
                <button v-on:click="search()"class="btn btn-outline-secondary" type="button"
                    id="button-addon2">search</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="card" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Thêm ảnh mới</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="issueinput6">Tên sản phẩm</label>
                                        <select v-model="add.id_san_pham" id="issueinput6" name="status"
                                            class="form-control" data-toggle="tooltip" data-trigger="hover"
                                            data-placement="top" data-title="Status" data-original-title=""
                                            title="">
                                            <template v-for="(value, key) in danhSachSanPham">
                                                <option v-bind:value="value.id">@{{ value.ten_san_pham }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="issueinput6">Hình ảnh</label>
                                        <div class="input-group">
                                            <input id="hinh_anh" class="form-control lfm" type="text"
                                                name="filepath">
                                            <span class="input-group-prepend">
                                                <a id="lfm" data-input="hinh_anh" data-preview="holder"
                                                    class="btn btn-primary lfm">
                                                    <i class="fa fa-picture-o"></i> Choose
                                                </a>
                                            </span>
                                        </div>
                                        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <button type="button" v-on:click="create($event)" class="btn btn-primary">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-7">
            <div class="card col-md-12" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Danh Sách ảnh</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <table class="table table-bordered mb-0" id="tableSanPham">
                            <thead>
                                <tr>
                                    <th class="text-nowrap text-center">#</th>
                                    <th class="text-nowrap text-center">Tên Sản Phẩm</th>
                                    <th class="text-nowrap text-center">Hình ảnh</th>
                                    <th class="text-nowrap text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(value, key) in ds_anh">
                                    <tr>
                                        <th class="text-nowrap text-center">@{{ key + 1 }}</th>
                                        <td>@{{ value.ten_san_pham }}</td>
                                        <td>
                                            <img style="height: 100px;width:125px"
                                            v-bind:src="value.hinh_anh" alt="">

                                        </td>
                                        <td class="text-center">
                                            <a href="" data-toggle="modal" data-toggle="modal"
                                                data-target="#editModal" v-on:click="editDanhMuc(value.id)"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href=""data-toggle="modal" data-toggle="modal"
                                                data-target="#deleteModal" v-on:click="deleteAnh(value.id)"><i
                                                    class="far fa-trash-alt"></i></a>
                                            {{-- <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModal"
                                                v-on:click="editAnh(value.id)">Edit</button>
                                            <button class="btn btn-danger" data-toggle="modal"
                                                data-target="#deleteModal"
                                                v-on:click="deleteAnh(value.id)">Delete</button> --}}
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <nav style="margin-top: 3px" aria-label="Page navigation example">
                            <ul class="pagination ">
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
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa ảnh</h5>
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
                    <button type="button" class="btn btn-danger" v-on:click="acceptDelete()"
                        data-dismiss="modal">Xóa Ảnh</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh Sửa Ảnh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" v-model="idEdit" hidden>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">Tên sản phẩm</label>
                            <select v-model="id_san_pham_edit" id="issueinput6" name="status" class="form-control"
                                data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Status"
                                data-original-title="" title="">
                                <template v-for="(value, key) in danhSachSanPham">
                                    <option v-bind:value="value.id">@{{ value.ten_san_pham }}</option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">Hình Ảnh</label>
                            <div class="input-group">
                                <input id="hinh_anh_edit" v-model="hinh_anh_edit" class="form-control edit_lfm"
                                    type="text" name="filepath">
                                <span class="input-group-prepend">
                                    <a id="lfm_edit" data-input="hinh_anh_edit" data-preview="holder"
                                        class="btn btn-primary edit_lfm">
                                        <i class="fa fa-picture-o"></i> Choose
                                    </a>
                                </span>
                            </div>
                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                        v-on:click="acceptUpdate()">Cập Nhật Ảnh</button>
                </div>
            </div>
        </div>
    </div>
</div>

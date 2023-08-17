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
        <div class="col-md-4">
            <div class="card" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Thêm mới danh mục</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">Tên Danh Mục</label>
                                            <input type="text" class="form-control" v-model="add.ten_danh_muc"
                                                name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="issueinput6">Danh Mục Cha</label>
                                        <select v-model="add.id_danh_muc_cha" id="issueinput6" name="status"
                                            class="form-control" data-toggle="tooltip" data-trigger="hover"
                                            data-placement="top" data-title="Status" data-original-title=""
                                            title="">
                                            <option value="">Root</option>
                                            <template v-for="(value, key) in danh_muc_cha_vue">
                                                <option v-bind:value="value.id">@{{ value.ten_danh_muc }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="issueinput6">Ảnh Đại Diện</label>
                                        <div class="input-group">
                                            <input id="hinh_anh" v-model="add.hinh_anh" class="form-control lfm"
                                                type="text" name="filepath">
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
                                <button type="button" v-on:click="create($event)" class="btn btn-primary">Thêm mới Danh
                                    Mục
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
                                    <h4 class="card-title">Danh sách danh mục</h4>
                                </div>
                            </div>

                        </div>
                        <div class="card-content collapse show">

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Tên Danh Mục</th>
                                            <td>Danh Mục Cha</th>
                                            <th>Ảnh đại diện</th>
                                            <th>Tình Trạng</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(value, key) in list_vue">
                                            <th class="text-center align-middle">@{{ key + 1 }}</th>
                                            <td class="align-middle">@{{ value.ten_danh_muc }}</td>
                                            <td class="text-center align-middle">@{{ value.id_danh_muc_cha === null ||value.id_danh_muc_cha == 0 ? 'Root' : value.ten_danh_muc_cha }}</td>
                                            <td>
                                                <img style="height: 50px;width:50px" v-bind:src="value.hinh_anh" alt="">
                                            </td>
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
                                                <a href="" data-toggle="modal"
                                                data-target="#editModal"
                                                v-on:click="editDanhMuc(value.id)"><i class="fas fa-edit"></i></a>
                                                <a href=""data-toggle="modal"
                                                data-target="#deleteModal"
                                                v-on:click="deleteDanhMuc(value.id)"><i
                                                        class="far fa-trash-alt"></i></a>
                                                {{-- <button class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModal"
                                                    v-on:click="editDanhMuc(value.id)">Edit</button>
                                                <button class="btn btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    v-on:click="deleteDanhMuc(value.id)">Delete</button> --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <nav style="margin-top: 3px" aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item" >
                                            <a class="page-link"  v-on:click="fetchCatagory(pagination.prev_page_url)" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Trang trước</span>
                                            </a>
                                        </li>
                                        <template v-for="(value, key) in url">
                                                {{-- <template v-if="key!=0 && key!=index">
                                                </template> --}}
                                            <li v-if="key!=0 && key!=index" class="page-item">
                                                <a  class="page-link" v-on:click="fetchCatagory(value.url)">@{{value.label}}</a>
                                            </li>
                                        </template>

                                        <li class="page-item" >

                                            <a class="page-link"  v-on:click="fetchCatagory(pagination.next_page_url)" aria-label="Next">
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
                    <h5 class="modal-title">Xóa Danh Mục Sản Phẩm</h5>
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
                        data-dismiss="modal">Xóa Danh Mục</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh Sửa Danh Mục Sản Phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" v-model="idEdit" hidden>
                    <div class="position-relative form-group">
                        <label>Tên Danh Mục</label>
                        <input placeholder="Nhập vào tên danh mục" v-model="ten_danh_muc_edit" type="text"
                            class="form-control">
                    </div>
                    <div class="position-relative form-group">
                        <label>Danh Mục Cha</label>
                        <select v-model="id_danh_muc_cha_edit" class="form-control">
                            <option>Root</option>
                            {{-- <option value="0" selected>Root</option> --}}
                            <template v-for="(value, key) in danh_muc_cha_vue">
                                <option v-bind:value="value.id">@{{ value.ten_danh_muc }}</option>
                            </template>
                        </select>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">Ảnh Đại Diện</label>
                            <div class="input-group">
                                <input id="hinh_anh_edit" v-model="hinh_anh_edit"
                                    class="form-control edit_lfm" type="text" name="filepath">
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
                    <div class="position-relative form-group">
                        <label>Tình Trạng</label>
                        <select v-model="is_open_edit" class="form-control">
                            <option value=1>Hiển Thị</option>
                            <option value=0>Tạm Tắt</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                        v-on:click="acceptUpdate()">Cập Nhật Danh Mục</button>
                </div>
            </div>
        </div>
    </div>
</div>

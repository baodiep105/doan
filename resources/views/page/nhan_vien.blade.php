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
                    <h4 class="card-title" id="basic-layout-colored-form-control">Quản lý nhân viên</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">User name</label>
                                            <input type="text" class="form-control" v-model="username"
                                                name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">password</label>
                                            <input type="password" class="form-control" v-model="password"
                                                name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">re-password</label>
                                            <input type="password" class="form-control" v-model="re_password"
                                                name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="issueinput6">Loại</label>
                                        <select v-model="role" name="status" class="form-control">
                                            <option value="1">Nhân Viên</option>
                                            <option value="0">Admin</option>
                                        </select>
                                    </div>
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
                                            <th>user name</th>
                                            <th>Email</th>
                                            <th>Chức Vụ</th>
                                            <th>Ngày tạo</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(value, key) in list_vue">
                                            <th class="text-center align-middle">@{{ key + 1 }}</th>
                                            <td class="align-middle">@{{ value.username }}</td>
                                            <td class="align-middle">@{{ value.email }}</td>
                                            <template v-if="value.id_loai==1">
                                                <td class="align-middle text-center">Nhân Viên</td>
                                            </template>
                                            <template v-else>
                                                <td class="align-middle text-center">Admin</td>
                                            </template>
                                            <td class="align-middle text-center">@{{ value.created_at }}</td>
                                            <td class="text-center">
                                                <a href="" data-toggle="modal" data-target="#editModal"
                                                    v-on:click="editDanhMuc(value.id)"><i class="fas fa-edit"></i></a>
                                                <a href=""data-toggle="modal" data-toggle="modal"
                                                    data-target="#deleteModal" v-on:click="deleteDanhMuc(value.id)"><i
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
                                            <a class="page-link"  v-on:click="fetchEmployee(pagination.prev_page_url)" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Trang trước</span>
                                            </a>
                                        </li>
                                        <template v-for="(value, key) in url">
                                                {{-- <template v-if="key!=0 && key!=index">
                                                </template> --}}
                                            <li v-if="key!=0 && key!=index" class="page-item">
                                                <a  class="page-link" v-on:click="fetchEmployee(value.url)">@{{value.label}}</a>
                                            </li>
                                        </template>

                                        <li class="page-item" >

                                            <a class="page-link"  v-on:click="fetchEmployee(pagination.next_page_url)" aria-label="Next">
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
                    <input type="text" class="form-control" v-model="idDelete" placeholder="Nhập vào id cần xóa"
                        hidden>
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
                    <input type="text" v-model="idEdit" hidden>
                    <div class="position-relative form-group">
                        <label>password</label>
                        <input placeholder="Nhập vào password" v-model="password_edit" type="password"
                            class="form-control">
                    </div>
                    <div class="position-relative form-group">
                        <label>re-password</label>
                        <input placeholder="Nhập vào re-password" v-model="re_password_edit" type="password"
                            class="form-control">
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

<div id="app">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-5" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title text-cnter" id="basic-layout-colored-form-control"> <i
                            class="fa-solid fa-gear"></i>Thêm Banner</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="issueinput6">baner 1</label>
                                            <div class="input-group">
                                                <input id="hinh_anh_1" v-model="add.hinh_anh_1" class="form-control lfm"
                                                    type="text" name="filepath">
                                                <span class="input-group-prepend">
                                                    <a data-input="hinh_anh_1" data-preview="holder"
                                                        class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i> Choose
                                                    </a>
                                                </span>
                                            </div>
                                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="issueinput6">banner 2</label>
                                            <div class="input-group">
                                                <input id="hinh_anh_2" v-model="add.hinh_anh_2" class="form-control lfm"
                                                    type="text" name="filepath">
                                                <span class="input-group-prepend">
                                                    <a data-input="hinh_anh_2" data-preview="holder"
                                                        class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i> Choose
                                                    </a>
                                                </span>
                                            </div>
                                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="issueinput6">banner 3</label>
                                            <div class="input-group">
                                                <input id="hinh_anh_3" v-model="add.hinh_anh_3" class="form-control lfm"
                                                    type="text" name="filepath">
                                                <span class="input-group-prepend">
                                                    <a data-input="hinh_anh_3" data-preview="holder"
                                                        class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i> Choose
                                                    </a>
                                                </span>
                                            </div>
                                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="issueinput6">banner 4</label>
                                            <div class="input-group">
                                                <input id="hinh_anh_4" v-model="add.hinh_anh_4" class="form-control lfm"
                                                    type="text" name="filepath">
                                                <span class="input-group-prepend">
                                                    <a data-input="hinh_anh_4" data-preview="holder"
                                                        class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i> Choose
                                                    </a>
                                                </span>
                                            </div>
                                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="issueinput6">banner 5</label>
                                            <div class="input-group">
                                                <input id="hinh_anh_5" v-model="add.hinh_anh_5" class="form-control lfm"
                                                    type="text" name="filepath">
                                                <span class="input-group-prepend">
                                                    <a data-input="hinh_anh_5" data-preview="holder"
                                                        class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i> Choose
                                                    </a>
                                                </span>
                                            </div>
                                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions right">
                            <button type="button" v-on:click="create($event)" class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card col-md-12" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Danh Sách banner</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <table class="table table-bordered mb-0" id="tableSanPham">
                            <thead>
                                <tr>
                                    <th class="text-nowrap text-center">#</th>
                                    <th class="text-nowrap text-center">banner 1</th>
                                    <th class="text-nowrap text-center">banner 2</th>
                                    <th class="text-nowrap text-center">banner 3</th>
                                    <th class="text-nowrap text-center">banner 4</th>
                                    <th class="text-nowrap text-center">banner 5</th>
                                    <th class="text-nowrap text-center">status</th>
                                    <th class="text-nowrap text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(value, key) in list_vue">
                                    <tr>
                                        <th class="text-nowrap text-center">@{{ key + 1 }}</th>
                                        <td>
                                            <img style="height: 50px;width:50px"
                                            v-bind:src="value.banner_1" alt="">
                                        </td>
                                        <td>
                                            <img style="height: 50px;width:50px"
                                                v-bind:src="value.banner_2" alt="">
                                        </td>
                                        <td>
                                            <img style="height: 50px;width:50px"
                                                v-bind:src="value.banner_3" alt="">
                                        </td>
                                        <td>
                                            <img style="height: 50px;width:50px"
                                                v-bind:src="value.banner_4" alt="">
                                        </td>
                                        <td>
                                            <img style="height: 50px;width:50px"
                                                v-bind:src="value.banner_5" alt="">
                                        </td>
                                        <td>
                                            <template v-if="value.is_open==1">
                                                <button v-on:click="doiTrangThai(value.id)"
                                                    class="btn btn-primary">hiển thị</button>
                                            </template>
                                            <template v-else>
                                                <button v-on:click="doiTrangThai(value.id)" class="btn btn-danger">tạm
                                                    tắt</button>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <a href="" data-toggle="modal" data-target="#editModal"
                                                v-on:click="editDanhMuc(value.id)"><i class="fas fa-edit"></i></a>
                                            <a href="" data-toggle="modal" data-target="#deleteModal"
                                                v-on:click="deleteBanner(value.id)"><i
                                                    class="far fa-trash-alt"></i></a>
                                            {{-- <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModal"
                                                v-on:click="editDanhMuc(value.id)">Edit</button>
                                            <button class="btn btn-danger" data-toggle="modal"
                                                data-target="#deleteModal"
                                                v-on:click="deleteBanner(value.id)">Delete</button> --}}
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <nav style="margin-top: 3px" aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item" >
                                    <a class="page-link"  v-on:click="fetchBanner(pagination.prev_page_url)" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Trang trước</span>
                                    </a>
                                </li>
                                <template v-for="(value, key) in url">
                                        {{-- <template v-if="key!=0 && key!=index">
                                        </template> --}}
                                    <li v-if="key!=0 && key!=index" class="page-item">
                                        <a  class="page-link" v-on:click="fetchBanner(value.url)">@{{value.label}}</a>
                                    </li>
                                </template>

                                <li class="page-item" >

                                    <a class="page-link"  v-on:click="fetchBanner(pagination.next_page_url)" aria-label="Next">
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
                    <h5 class="modal-title">Xóa Banner</h5>
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
                        data-dismiss="modal">Xóa Banner</button>
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
                    <input type="text" v-model="update.idEdit" hidden>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">baner 1</label>
                            <div class="input-group">
                                <input id="hinh_anh_1_edit" v-model="update.hinh_anh_1_edit" class="form-control lfm"
                                    type="text" name="filepath">
                                <span class="input-group-prepend">
                                    <a data-input="hinh_anh_1_edit" data-preview="holder"
                                        class="btn btn-primary lfm">
                                        <i class="fa fa-picture-o"></i> Choose
                                    </a>
                                </span>
                            </div>
                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">banner 2</label>
                            <div class="input-group">
                                <input id="hinh_anh_2_edit" v-model="update.hinh_anh_2_edit" class="form-control lfm"
                                    type="text" name="filepath">
                                <span class="input-group-prepend">
                                    <a data-input="hinh_anh_2_edit" data-preview="holder"
                                        class="btn btn-primary lfm">
                                        <i class="fa fa-picture-o"></i> Choose
                                    </a>
                                </span>
                            </div>
                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">banner 3</label>
                            <div class="input-group">
                                <input id="hinh_anh_3_edit" v-model="update.hinh_anh_3_edit" class="form-control lfm"
                                    type="text" name="filepath">
                                <span class="input-group-prepend">
                                    <a data-input="hinh_anh_3_edit" data-preview="holder"
                                        class="btn btn-primary lfm">
                                        <i class="fa fa-picture-o"></i> Choose
                                    </a>
                                </span>
                            </div>
                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">banner 4</label>
                            <div class="input-group">
                                <input id="hinh_anh_4_edit" v-model="update.hinh_anh_4_edit" class="form-control lfm"
                                    type="text" name="filepath">
                                <span class="input-group-prepend">
                                    <a data-input="hinh_anh_4_edit" data-preview="holder"
                                        class="btn btn-primary lfm">
                                        <i class="fa fa-picture-o"></i> Choose
                                    </a>
                                </span>
                            </div>
                            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="issueinput6">banner 5</label>
                            <div class="input-group">
                                <input id="hinh_anh_5_edit" v-model="update.hinh_anh_5_edit" class="form-control lfm"
                                    type="text" name="filepath">
                                <span class="input-group-prepend">
                                    <a data-input="hinh_anh_5_edit" data-preview="holder"
                                        class="btn btn-primary lfm">
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
                        v-on:click="acceptUpdate()">Cập Nhật Danh Mục</button>
                </div>
            </div>
        </div>
    </div>
</div>

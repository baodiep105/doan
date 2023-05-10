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
        <div class="col-md-6">
            <div class="card" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Quản lý Size & Mau</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">Size</label>
                                            <input type="number" v-model='size'class="form-control"
                                                name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <button type="button" v-on:click="create($event)" class="btn btn-primary">Thêm
                                </button>
                            </div>
                        </form>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4 class="card-title">Danh sách Size</h4>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-content collapse show">

                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>#</th>
                                                        <th>Size</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(value, key) in list_size">
                                                        <th class="text-center align-middle">@{{ key + 1 }}</th>
                                                        <td class="align-middle">@{{ value.size }}</td>
                                                        <td class="text-center">
                                                            <a href="" data-toggle="modal"
                                                                data-target="#deleteModal"
                                                                v-on:click="deletekhuyenmai(value.id)"><i
                                                                    class="far fa-trash-alt"></i></a>
                                                            {{-- <button class="btn btn-danger" data-toggle="modal"
                                                                data-target="#deleteModal"
                                                                v-on:click="deletekhuyenmai(value.id)">Delete</button> --}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <nav style="margin-top: 5px" aria-label="Page navigation example">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item" >
                                                        <a class="page-link"  v-on:click="fetchSize(pagination.prev_page_url)" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                            <span class="sr-only">Trang trước</span>
                                                        </a>
                                                    </li>
                                                    <template v-for="(value, key) in url_size">
                                                            {{-- <template v-if="key!=0 && key!=index">
                                                            </template> --}}
                                                        <li v-if="key!=0 && key!=index_size" class="page-item">
                                                            <a  class="page-link" v-on:click="fetchSize(value.url)">@{{value.label}}</a>
                                                        </li>
                                                    </template>

                                                    <li class="page-item" >

                                                        <a class="page-link"  v-on:click="fetchSize(pagination.next_page_url)" aria-label="Next">
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
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="height: auto">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-colored-form-control">Quản lý Màu</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">Màu</label>
                                            <input type="text" v-model='mau'class="form-control"
                                                name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="userinput1">Mã Màu</label>
                                            <input type="text" v-model='hex'class="form-control"
                                                name="ten_danh_muc">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions right">
                                <button type="button" v-on:click="createMau($event)" class="btn btn-primary">Thêm
                                </button>
                            </div>
                        </form>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4 class="card-title">Danh Sách Màu</h4>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-content collapse show">

                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>#</th>
                                                        <th>Màu</th>
                                                        <th>Mã màu</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(value, key) in list_mau">
                                                        <th class="text-center align-middle">@{{ key + 1 }}</th>
                                                        <td class="align-middle">@{{ value.ten_mau }}</td>
                                                        <td class="align-middle">@{{ value.hex }}</td>
                                                        <td class="text-center">
                                                            {{-- <a href="" data-toggle="modal" data-target="#editModal" v-on:click="editSanPham(value.id)"><i class="fas fa-edit"></i></a> --}}
                                                            <a href="" data-toggle="modal"data-toggle="modal"
                                                                data-target="#deleteMauModal"
                                                                v-on:click="deleteMau(value.id)"><i
                                                                    class="far fa-trash-alt"></i></a>
                                                            {{-- <button class="btn btn-danger" data-toggle="modal"
                                                                data-target="#deleteMauModal"
                                                                v-on:click="deleteMau(value.id)">Delete</button> --}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <nav style="margin-top: 5px" aria-label="Page navigation example">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item" >
                                                        <a class="page-link"  v-on:click="fetchMau(pagination.prev_page_url)" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                            <span class="sr-only">Trang trước</span>
                                                        </a>
                                                    </li>
                                                    <template v-for="(value, key) in url">
                                                            {{-- <template v-if="key!=0 && key!=index">
                                                            </template> --}}
                                                        <li v-if="key!=0 && key!=index" class="page-item">
                                                            <a  class="page-link" v-on:click="fetchMau(value.url)">@{{value.label}}</a>
                                                        </li>
                                                    </template>

                                                    <li class="page-item" >

                                                        <a class="page-link"  v-on:click="fetchMau(pagination.next_page_url)" aria-label="Next">
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
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa Size</h5>
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
    <div class="modal fade" id="deleteMauModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa Màu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" v-model="idDeleteMau"
                        placeholder="Nhập vào id cần xóa" hidden>
                    Bạn có chắc chắn muốn xóa? Điều này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" v-on:click="acceptDeleteMau()"
                        data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

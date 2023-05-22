<div id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <h4 class="card-title">Danh sách đánh giá  </h4>
                        </div>
                        {{-- row justify-content-end --}}
                        <div class="col-md-8 row justify-content-end">
                            <div class="form-check ">
                                <input   type="radio" v-model="removelines" name="removelines" v-bind:value="0" v-on:change="changeData()">
                                <label class="form-check-label" for="flexRadioDefault1"  >
                                   Tất cả
                                </label>
                            </div>
                            <div class="form-check ml-3">
                                <input   type="radio" v-model="removelines" name="removelines" v-bind:value="1" v-on:change="changeData()">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Chưa trả lời
                                </label>
                            </div>
                            <div class="form-check ml-3">
                                <input   type="radio" v-model="removelines" name="removelines" v-bind:value="2" v-on:change="changeData()">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Đã trả lời
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Nội dung</th>
                                    <th>sao</th>
                                    <th>sản phẩm</th>
                                    <th>Ngày Đăng</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value, key) in list_vue">
                                    <th class="text-center align-middle">@{{ key + 1 }}</th>
                                    <td class="text-center align-middle">@{{ value.email }}</td>
                                    <td class="text-center align-middle">@{{ value.content }}</td>
                                    <td class="text-center align-middle">@{{ value.rate }}</td>
                                    <td class="text-center align-middle">@{{ value.ten_san_pham }}</td>
                                    <td class="text-center align-middle"> @{{ value.created_at }}</td>
                                    <td class="text-center">
                                        <a href=""data-toggle="modal" data-target="#deleteModal"
                                            v-on:click="deleteDanhMuc(value.id)"><i class="far fa-trash-alt"></i></a>
                                        {{-- <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" v-on:click="deleteDanhMuc(value.id)">Delete</button> --}}
                                        <a href=""data-toggle="modal" data-target="#replyModel"
                                            v-on:click="getDanhGia(value.id)" style="margin-left: 5px"><i class="fas fa-comment-dots"></i></a>
                                        {{-- <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" v-on:click="deleteDanhMuc(value.id)">Delete</button> --}}
                                    </td>

                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa User</h5>
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
                        data-dismiss="modal">Xóa</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="replyModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@{{ email }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <i class="fa-solid fa-user"></i> --}}

                    <div class="row">
                        <div class="col-md-1 ">
                            <input type="text" class="form-control" v-model="id" placeholder="Nhập vào id cần xóa"
                                hidden>
                            <img style="width: 25px; height: 25px;"
                                src="data:image/png;base64,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"
                                alt=""> </label>
                        </div>
                        <div class="col-9 bl-4">
                            <p style="font-size: 15px">@{{ content }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col row justify-content-end">
                            <template v-if="child_content!=null">
                                <a  v-show="value"id='update' class='update' v-on:click="updateReply()"style="margin-right: 5px" ><i class="far fa-edit"></i></a>
                            </template>
                            <p style="font-size: 15px"> @{{ child_content }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="input-group mb-3">
                        <input type="text" v-model="reply" class="form-control" placeholder="Recipient's username"
                            aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-secondary" v-on:click="replyDanhGia()" type="button"
                            id="button-addon2">Trả lời</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

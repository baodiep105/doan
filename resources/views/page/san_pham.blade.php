<div id="app">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                            <label>Tên sản phẩm</label>
                                            <input v-model="sanPhamCreate.ten_san_pham" type="text"
                                                class="form-control" placeholder="Nhập vào ten sản phẩm">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                            <label>Thương hiệu</label>
                                            <input v-model="sanPhamCreate.brand" type="text" class="form-control"
                                                placeholder="Nhập vào Thương hiệu">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                            <label>Giá Bán</label>
                                            <input v-model="sanPhamCreate.gia_ban" type="number" min="1" class="form-control"
                                                placeholder="Nhập vào giá bán">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset class="form-group">
                                            <label for="placeTextarea">Mô Tả Ngắn</label>
                                            <textarea v-model="sanPhamCreate.mo_ta_ngan" class="form-control" cols="30" rows="5"
                                                placeholder="Nhập vào mô tả ngắn"></textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="position-relative form-group">

                                    <label for="placeTextarea">Mô Tả Chi Tiết</label>
                                    <textarea v-model="sanPhamCreate.mo_ta_chi_tiet" class="form-control" cols="30" rows="5"
                                        placeholder="Nhập vào mô tả chi tiết"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label>Danh Mục</label>
                                            <select v-model="sanPhamCreate.id_danh_muc" class="custom-select block">
                                                {{-- @foreach ($danhSachDanhMuc as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->ten_danh_muc }}</option>
                                            @endforeach --}}

                                                <template v-for="(value, key) in danhSachDanhMuc">
                                                    <option v-bind:value="value.id">@{{ value.ten_danh_muc }}</option>
                                                </template>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="form-group">
                                            <label>Trạng Thái</label>
                                            <select v-model="sanPhamCreate.is_open" id="is_open"
                                                class="custom-select block">
                                                <option value=1>Hiển Thị</option>
                                                <option value=0>Tạm tắt</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <button v-on:click="create()" class="mt-1 btn btn-primary">Thêm Mới Sản Phẩm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

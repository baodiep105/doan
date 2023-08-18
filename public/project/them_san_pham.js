
    new Vue({
        el  :   '#app',
        data:   {
            danhSachDanhMuc :   [],
            danhSachSanPham :   [],
            sanPhamCreate   :   {
                ten_san_pham    :   '',
                gia_ban         :   0,
                gia_khuyen_mai  :   0,
                brand           :   '',
                mo_ta_ngan      :   '',
                mo_ta_chi_tiet  :   '',
                id_danh_muc     :   '',
                is_open         :   1,
            },
        },
        created() {
            this.loadData();
        },
        methods :   {
            loadData() {
                axios
                    .get('/admin/san-pham/load-data')
                    .then((res) => {
                        this.danhSachDanhMuc = res.data.danhSachDanhMuc;
                    });
            },
            create() {
                axios
                    .post('/admin/san-pham/create', this.sanPhamCreate)
                    .then((res) => {
                        toastr.success("Đã thêm mới sản phẩm thành công!!!");
                        this.loadData();
                    })
                    .catch((res) => {
                        var danh_sach_loi = res.response.data.errors;
                        $.each(danh_sach_loi, function(key, value){
                            toastr.error(value[0]);
                        });
                    });
            },
        },
    });

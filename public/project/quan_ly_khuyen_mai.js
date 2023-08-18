
new Vue({
    el: "#app",
    data: {
        ds_san_pham: '',
        list_vue: [],
        idDelete: 0,
        inputSearch: '',
        add: {
            id_san_pham: "",
            ty_le: "",
            is_open: 1,
        },
        update: {
            idEdit: 0,
            id_san_pham_edit: "",
            ty_le_edit: "",
            is_open_edit: 1,
        },
        pagination: {},
        url: [],
        index: 0,
        link:'',
    },

    created() {
        this.fetchKhuyenMai();
    },

    methods: {
        fetchKhuyenMai(page_url) {
            page_url = page_url || "/admin/quan-ly-khuyen-mai/get-data";
            console.log(page_url);
            this.link=page_url;
            let vm = this;
            let meta = {};
            let link = {};
            axios
                .get(page_url)
                .then((res) => {
                    this.ds_san_pham = res.data.sanPham;
                    this.list_vue = res.data.ds_khuyen_mai.data;

                    this.url=res.data.ds_khuyen_mai.links;
                    link = {
                        first_page_url: res.data.ds_khuyen_mai.first_page_url,
                        last_page_url: res.data.ds_khuyen_mai.last_page_url,
                        next_page_url: res.data.ds_khuyen_mai.next_page_url,
                        prev_page_ur: res.data.ds_khuyen_mai.prev_page_url
                    };
                    // console.log(link)
                    meta = {
                        "current_page": res.data.ds_khuyen_mai.current_page,
                        "from": res.data.ds_khuyen_mai.from,
                        "last_page": res.data.ds_khuyen_mai.last_page,
                        "path": res.data.ds_khuyen_mai.path,
                        "to": res.data.ds_khuyen_mai.to,
                        "total": res.data.ds_khuyen_mai.total,
                    }
                    this.index=this.url.length-1;
                    console.log(this.index);
                    // console.log(this.danhSachSanPham);
                    vm.paginate(link, meta);
                });

        },
        paginate(link, meta) {
            // console.log()
            let paginate = {
                current_page: meta.current_page,
                // "from": meta.from,
                last_page: meta.last_page,
                next_page_url:link.next_page_url ,
                prev_page_url:link.prev_page_url
            }
            this.pagination = paginate;
            // console.log(!);
        },
        deleteSanPham(id) {
            this.id_delete = id;
            console.log(this.id_delete);
        },
        create(e) {
            e.preventDefault();
            axios
                .post('/admin/quan-ly-khuyen-mai/create', this.add)
                .then((res) => {
                    // console.log(res);
                    toastr.success('Thêm mới thành công!');
                    this.fetchKhuyenMai( this.link);
                })
                .catch((res) => {
                    var danh_sach_loi = res.response.data.errors;
                    $.each(danh_sach_loi, function (key, value) {
                        toastr.error(value[0]);
                    });
                });
        },
        // getData() {
        //     axios
        //         .get('/admin/quan-ly-khuyen-mai/getData')
        //         .then((res) => {
        //             this.ds_san_pham = res.data.sanPham;
        //             this.list_vue = res.data.ds_khuyen_mai.data;
        //         })
        // },

        doiTrangThai(id) {
            axios
                .get('/admin/quan-ly-khuyen-mai/change-status/' + id)
                .then((res) => {
                    if (res.data.trangThai) {
                        toastr.success('Đã đổi trạng thái thành công!');
                        // Tình trạng mới là true
                        this.fetchKhuyenMai( this.link);
                    } else {
                        toastr.error('Vui lòng không can thiệp hệ thống!');
                    }
                })
        },

        // search() {
        //     var payload = {
        //         'search': this.inputSearch,
        //     };
        //     axios
        //         .post('/admin/quan-ly-khuyen-mai/search', payload)
        //         .then((res) => {
        //             this.list_vue = res.data.data;
        //             console.log( res.data.data);
        //         });
        // },

        deletekhuyenmai(id) {
            this.idDelete = id;
            console.log(id);
            console.log(this.idDelete);
        },

        acceptDelete() {
            axios
                .delete('/admin/quan-ly-khuyen-mai/delete/' + this.idDelete)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã xóa khuyến mãi thành công');
                        this.fetchKhuyenMai( this.link);
                    } else {
                        toastr.error('mã khuyến mãi không tồn tại');
                    }
                })
        },

        editDanhMuc(id) {
            this.update.idEdit = id;
            axios
                .get('/admin/quan-ly-khuyen-mai/edit/' + id)
                .then((res) => {
                    if (res.data.status) {
                        this.update.ty_le_edit = res.data.khuyen_mai.ty_le;
                        this.update.id_san_pham_edit = res.data.khuyen_mai.id_san_pham;
                        this.update.is_open_edit = res.data.khuyen_mai.is_open;
                        console.log(this.update);
                    } else {
                        toastr.error('Danh mục không tồn tại');
                    }
                })
        },
        acceptUpdate() {
            axios
                .post('/admin/quan-ly-khuyen-mai/update', this.update)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã cập nhật khuyến mãi thành công');
                        this.fetchKhuyenMai( this.link);
                    } else {
                        toastr.error('Id khuyến mãi không tồn tại');
                    }
                })
                .catch((res) => {
                    var danh_sach_loi = res.response.data.errors;
                    $.each(danh_sach_loi, function (key, value) {
                        toastr.error(value[0]);
                    })
                })
        },
    },
});

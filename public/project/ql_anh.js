
new Vue({
    el: '#app',
    data: {
        danhSachSanPham: [],
        danhSachDanhMuc: [],
        id_delete: 0,
        inputSearch: '',
        idEdit: 0,
        ds_anh: [],
        anh: '',
        hinh_anh_edit: '',
        id_san_pham_edit: null,
        add: {
            hinh_anh: '',
            id_san_pham: null,
        },
        pagination: {},
        url: [],
        index: 0,
        link:'',
    },
    created() {
        this.fetchCustomers();
    },
    methods: {
        fetchCustomers(page_url) {
            page_url = page_url || "/admin/quan-ly-anh/getData";
            // console.log("/admin/san-pham/getData");
            this.link=page_url;
            let vm = this;
            let meta = {};
            let link = {};
            axios
                .get(page_url)
                .then((res) => {
                    console.log(res.data);
                    this.danhSachSanPham = res.data.data;
                    // this.danhSachDanhMuc = res.data.danhSachDanhMuc;
                    this.ds_anh = res.data.sanPham.data;
                    this.url = res.data.sanPham.links;
                    link = {
                        first_page_url: res.data.sanPham.first_page_url,
                        last_page_url: res.data.sanPham.last_page_url,
                        next_page_url: res.data.sanPham.next_page_url,
                        prev_page_ur: res.data.sanPham.prev_page_url
                    };
                    // console.log(link)
                    meta = {
                        "current_page": res.data.sanPham.current_page,
                        "from": res.data.sanPham.from,
                        "last_page": res.data.sanPham.last_page,
                        "path": res.data.sanPham.path,
                        "to": res.data.sanPham.to,
                        "total": res.data.sanPham.total,
                    }
                    this.index = this.url.length - 1;
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
                next_page_url: link.next_page_url,
                prev_page_url: link.prev_page_url
            }
            this.pagination = paginate;
            console.log(this.pagination);
        },
        create(e) {
            e.preventDefault();
            this.add.hinh_anh = $("#hinh_anh").val();
            axios
                .post('/admin/quan-ly-anh/create', this.add)
                .then((res) => {
                    toastr.success("Đã thêm ảnh mới!");
                    this.fetchCustomers(this.link);
                })
                .catch((res) => {
                    var errors = res.response.data.errors;
                    $.each(errors, function (k, v) {
                        toastr.error(v[0]);
                    });
                });
        },

        deleteAnh(id) {
            this.id_delete = id;
            console.log(this.id_delete);
        },
        acceptDelete() {
            axios
                .delete('/admin/quan-ly-anh/delete/' + this.id_delete)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã xóa ảnh thành công');
                        this.fetchCustomers(this.link);
                    } else {
                        toastr.error('ảnh không tồn tại');
                    }
                })
        },
        // loadData() {
        //     axios
        //         .get('/admin/quan-ly-anh/getData')
        //         .then((res) => {
        //             this.danhSachSanPham = res.data.data;
        //             this.ds_anh = res.data.sanPham;
        //         });
        // },

        search() {
            var payload = {
                'search': this.inputSearch,
            };
            axios
                .post('/admin/quan-ly-anh/search', payload)
                .then((res) => {
                    this.ds_anh = res.data.data;
                });
        },

        editDanhMuc(id) {
            this.idEdit = id;
            axios
                .get('/admin/quan-ly-anh/edit/' + id)
                .then((res) => {
                    if (res.data.status) {
                        this.hinh_anh_edit = res.data.anh.hinh_anh;
                        this.id_san_pham_edit = res.data.anh.id_san_pham;
                        console.log(this.id_san_pham_edit);
                    } else {
                        toastr.error('Ảnh không tồn tại');
                    }
                })
        },

        acceptUpdate() {
            this.anh = $("#hinh_anh_edit").val();
            var payload = {
                'id': this.idEdit,
                'id_san_pham': this.id_san_pham_edit,
                'hinh_anh': this.anh,
            };
            axios
                .post('/admin/quan-ly-anh/update', payload)
                .then((res) => {
                    console.log(res);
                    this.fetchCustomers(this.link);
                    toastr.success('Cập nhật thành công ảnh!');

                })
                .catch((res) => {
                    var danh_sach_loi = res.response.data.errors;
                    $.each(danh_sach_loi, function (key, value) {
                        toastr.error(value[0]);
                    });
                });
        },
    },
});

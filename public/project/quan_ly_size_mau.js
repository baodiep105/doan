
new Vue({
    el: "#app",
    data: {
        list_mau: [],
        list_size: [],
        idDelete: 0,
        idDeleteMau: 0,
        inputSearch: '',
        size: '',
        mau: '',
        hex: '',
        pagination: {},
        url: [],
        index: 0,

        pagination_size: {},
        url_size: [],
        index_size: 0,
        link_size:'',
        link_mau:'',
    },

    created() {
        this.fetchMau();
        this.fetchSize();
    },

    methods: {
        fetchMau(page_url) {
            page_url = page_url || "/admin/quan-ly-mau/getData";
            console.log(page_url);
            this.link_mau=page_url;
            let vm = this;
            let meta = {};
            let link = {};
            axios
                .get(page_url)
                .then((res) => {
                    this.list_mau = res.data.mau.data;
                    this.url = res.data.mau.links;
                    link = {
                        first_page_url: res.data.mau.first_page_url,
                        last_page_url: res.data.mau.last_page_url,
                        next_page_url: res.data.mau.next_page_url,
                        prev_page_ur: res.data.mau.prev_page_url
                    };
                    // console.log(link)
                    meta = {
                        "current_page": res.data.mau.current_page,
                        "from": res.data.mau.from,
                        "last_page": res.data.mau.last_page,
                        "path": res.data.mau.path,
                        "to": res.data.mau.to,
                        "total": res.data.mau.total,
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
            // console.log(!);
        },
        fetchSize(page_url) {
            page_url = page_url || "/admin/quan-ly-size/getData";
            console.log(page_url);
            this.size=page_url;
            let vm = this;
            let meta = {};
            let link = {};
            axios
                .get(page_url)
                .then((res) => {
                    this.list_size = res.data.size.data;
                    this.url_size = res.data.size.links;
                    link = {
                        first_page_url: res.data.size.first_page_url,
                        last_page_url: res.data.size.last_page_url,
                        next_page_url: res.data.size.next_page_url,
                        prev_page_ur: res.data.size.prev_page_url
                    };
                    // console.log(link)
                    meta = {
                        "current_page": res.data.size.current_page,
                        "from": res.data.size.from,
                        "last_page": res.data.size.last_page,
                        "path": res.data.size.path,
                        "to": res.data.size.to,
                        "total": res.data.size.total,
                    }
                    this.index_size = res.data.size.links.length - 1;
                    // console.log(  this.url_size.length );
                    // console.log(this.danhSachSanPham);
                    vm.paginate_size(link, meta);
                });

        },
        paginate_size(link, meta) {
            // console.log()
            let paginate = {
                current_page: meta.current_page,
                // "from": meta.from,
                last_page: meta.last_page,
                next_page_url: link.next_page_url,
                prev_page_url: link.prev_page_url
            }
            this.pagination_size = paginate;
            // console.log(!);
        },
        create(e) {
            e.preventDefault();
            var payload = {
                'size': this.size,
            };
            axios
                .post('/admin/quan-ly-size/create', payload)
                .then((res) => {
                    // console.log(res);
                    toastr.success('Thêm mới thành công!');
                    this.fetchSize(this.link_size);
                })
                .catch((res) => {
                    var danh_sach_loi = res.response.data.errors;
                    $.each(danh_sach_loi, function (key, value) {
                        toastr.error(value[0]);
                    });
                });
        },
        createMau(e) {
            e.preventDefault();
            var payload = {
                'ten_mau': this.mau,
                'ma_mau': this.hex,
            };
            axios
                .post('/admin/quan-ly-mau/create', payload)
                .then((res) => {
                    // console.log(res);
                    toastr.success('Thêm mới thành công!');
                    this.fetchMau(this.link_mau);
                })
                .catch((res) => {
                    var danh_sach_loi = res.response.data.errors;
                    $.each(danh_sach_loi, function (key, value) {
                        toastr.error(value[0]);
                    });
                });
        },
        getData() {
            axios
                .get('/admin/quan-ly-size/getData')
                .then((res) => {
                    this.list_size = res.data.size;
                })
        },

        search() {
            var payload = {
                'search': this.inputSearch,
            };
            axios
                .post('/admin/quan-ly-nhan-vien/search', payload)
                .then((res) => {
                    this.list_vue = res.data.data;
                });
        },

        deletekhuyenmai(id) {
            this.idDelete = id;
            console.log(id);
            console.log(this.idDelete);
        },

        acceptDelete() {
            axios
                .delete('/admin/quan-ly-size/delete/' + this.idDelete)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã xóa thành công');
                        this.fetchSize(this.link_size);
                    } else {
                        toastr.error('mã size không tồn tại');
                    }
                })
        },
        deleteMau(id) {
            this.idDeleteMau = id;
            console.log(id);
            console.log(this.idDeleteMau);
        },

        acceptDeleteMau() {
            axios
                .delete('/admin/quan-ly-mau/delete/' + this.idDeleteMau)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã xóa  thành công');
                        this.fetchMau(this.link_mau);
                    } else {
                        toastr.error('mã màu không tồn tại');
                    }
                })
        },
    },
});

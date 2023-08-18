new Vue({
    el: "#app",
    data: {
        list_vue: [],
        idDelete: 0,
        inputSearch: '',
        donhang: [],
        status: '',
        pagination: {},
        url: [],
        index: 0,
        link:''
    },

    created() {
        this.fetchDonHang();
    },

    methods: {
        fetchDonHang(page_url) {
            page_url = page_url || "/admin/quan-ly-don-hang/get-data";
            console.log(page_url);
            this.link=page_url;
            let vm = this;
            let meta = {};
            let link = {};
            axios
                .get(page_url)
                .then((res) => {
                    this.list_vue = res.data.donhang.data;

                    this.url = res.data.donhang.links;
                    link = {
                        first_page_url: res.data.donhang.first_page_url,
                        last_page_url: res.data.donhang.last_page_url,
                        next_page_url: res.data.donhang.next_page_url,
                        prev_page_ur: res.data.donhang.prev_page_url
                    };
                    // console.log(link)
                    meta = {
                        "current_page": res.data.donhang.current_page,
                        "from": res.data.donhang.from,
                        "last_page": res.data.donhang.last_page,
                        "path": res.data.donhang.path,
                        "to": res.data.donhang.to,
                        "total": res.data.donhang.total,
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

        doiTrangThai(id, event) {
            var payload = {
                'value': event,
            }
            axios
                .put('/admin/quan-ly-don-hang/change-status/' + id, payload)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã đổi trạng thái thành công!');
                        // Tình trạng mới là true
                        this.fetchDonHang( this.link)(this.link);
                    } else {
                        toastr.error('Vui lòng không can thiệp hệ thống!');
                    }
                })
        },

        search() {
            console.log(this.inputSearch);
            var payload = {
                'search': this.inputSearch,
            };
            console.log(payload);
            axios
                .post('/admin/quan-ly-don-hang/search', payload)
                .then((res) => {
                    this.list_vue = res.data.data;
                });
        },

        deleteDanhMuc(id) {
            this.idDelete = id;
        },

        acceptDelete() {
            console.log(this.idDelete);
            axios
                .delete('/admin/quan-ly-don-hang/delete/' + this.idDelete)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã xóa đơn hàng thành công');
                        this.fetchDonHang( this.link);
                    } else {
                        toastr.error('Đơn hàng không tồn tại');
                    }
                })
        },
    },
});

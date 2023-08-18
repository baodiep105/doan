
        new Vue({
            el: "#app",
            data: {
                username: '',
                password: '',
                re_password:'' ,
                username_edit:'',
                password_edit:'',
                re_password_edit:'',
                list_vue: [],
                idDelete: 0,
                idEdit: 0,
                inputSearch: '',
                role:0,
                pagination: {},
                url:[],
                index:0,
                link:'',
            },

            created() {
                this.fetchCustomers();
            },

            methods: {
                fetchCustomers(page_url) {
                    page_url = page_url || "/admin/quan-ly-nhan-vien/get-data";
                    this.link=page_url;
                    console.log(page_url);
                    let vm = this;
                    let meta = {};
                    let link = {};
                    axios
                        .get(page_url)
                        .then((res) => {
                            this.list_vue = res.data.user.data;

                            this.url=res.data.danhSachSanPham.links;
                            link = {
                                first_page_url: res.data.danhSachSanPham.first_page_url,
                                last_page_url: res.data.danhSachSanPham.last_page_url,
                                next_page_url: res.data.danhSachSanPham.next_page_url,
                                prev_page_ur: res.data.danhSachSanPham.prev_page_url
                            };
                            // console.log(link)
                            meta = {
                                "current_page": res.data.danhSachSanPham.current_page,
                                "from": res.data.danhSachSanPham.from,
                                "last_page": res.data.danhSachSanPham.last_page,
                                "path": res.data.danhSachSanPham.path,
                                "to": res.data.danhSachSanPham.to,
                                "total": res.data.danhSachSanPham.total,
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
                create(e) {
                    e.preventDefault();
                    var payload = {
                        'username': this.username,
                        'password': this.password,
                        're_password': this.re_password,
                        'role':this.role,
                    };
                    console.log(payload)
                    axios
                        .post('/admin/quan-ly-nhan-vien/create', payload)
                        .then((res) => {
                            if(res.data.status){
                            // console.log(res);
                            toastr.success('Thêm mới thành công!');
                            this.fetchCustomers(this.link);}
                            else{
                                var danh_sach_loi = res.data.error;
                                 $.each(danh_sach_loi, function(key, value) {
                                toastr.error(value[0]);
                            });
                            }
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

                deleteDanhMuc(id) {
                    this.idDelete = id;
                },

                acceptDelete() {
                    axios
                        .delete('/admin/quan-ly-nhan-vien/delete/' + this.idDelete)
                        .then((res) => {
                            if (res.data.status) {
                                toastr.success('Đã xóa nhân viên thành công');
                                this.fetchCustomers(this.link);
                            } else {
                                toastr.error('nhân viên không tồn tại');
                            }
                        })
                },

                editDanhMuc(id) {
                    this.idEdit = id;
                },

                acceptUpdate() {
                    var payload = {
                        'id': this.idEdit,
                        'password': this.password_edit,
                        're_password': this.re_password_edit,
                    };

                    // console.log(payload);

                    axios
                        .post('/admin/quan-ly-nhan-vien/update', payload)
                        .then((res) => {
                            if(res.data.status){
                            toastr.success('Cập nhật thành công!');
                            this.fetchCustomers(this.link);}
                            else{
                                var danh_sach_loi = res.data.error;
                                 $.each(danh_sach_loi, function(key, value) {
                                toastr.error(value[0]);
                            });
                            }
                        })
                },
            },
        });

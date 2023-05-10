new Vue({
    el: "#app",
    data: {
        list_vue: [],
        idDelete: 0,
        inputSearch: '',
        list_replied: [],
        list_non_reply: [],
        removelines: '0',
        a : '',
        list_all:[],
        id: '',
        content: '',
        email: '',
        child_content: '',
        id_san_pham: '',
        reply:'',
    },

    created() {
        this.getData();
    },

    methods: {
        changeData() {
            this.a=this.removelines;
            console.log(this.a);
            this.list_vue = this.removelines == '0' ? this.list_all : this.removelines == '1'  ? this.list_non_reply : this.list_replied;
            // this.removelines=null;

        },
        updateReply(){
            this.reply=this.child_content
            this.child_content="";
        },
        replyDanhGia() {
            this.child_content=this.reply;
            // console.log(this.child_content);
            var payload={
                'reply': this.reply,
            }
            this.reply='';
            axios
                .put('/admin/quan-ly-danh-gia/reply/' + this.id, payload)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('trả lời đánh giá thành công!');
                        // Tình trạng mới là true
                        this.getData();
                    }
                })
        },
        getDanhGia($id) {
            axios
                .get('/admin/quan-ly-danh-gia/get-danh-gia/' + $id)
                .then((res) => {
                    this.id = res.data.data.id;
                    this.content = res.data.data.content;
                    this.email = res.data.data.email;
                    this.child_content=res.data.data.children_content;
                    this.id_san_pham=res.data.data.id_san_pham;
                    console.log(res.data.data.children_content);
                    // console.log(this);
                })
        },

        getData() {
            axios
                .get('/admin/quan-ly-danh-gia/getData')
                .then((res) => {
                    this.list_vue = res.data.all;
                    this.list_replied = res.data.reply;
                    this.list_all = res.data.all;
                    this.list_non_reply = res.data.non_rep;

                })
        },

        doiTrangThai(id) {
            console.log(id);
            axios
                .get('/admin/quan-ly-user/changeStatus/' + id)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã đổi trạng thái thành công!');
                        // Tình trạng mới là true
                        this.getData();
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
                .post('/admin/quan-ly-user/search', payload)
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
                .delete('/admin/quan-ly-danh-gia/delete/' + this.idDelete)
                .then((res) => {
                    if (res.data.status) {
                        toastr.success('Đã xóa danh mục thành công');
                        this.getData();
                    } else {
                        toastr.error('Danh mục không tồn tại');
                    }
                })
        },

        editDanhMuc(id) {
            this.idEdit = id;
            axios
                .get('/admin/danh-muc/edit/' + id)
                .then((res) => {
                    console.log(res);
                    if (res.data.status) {
                        this.ten_danh_muc_edit = res.data.danhMuc.ten_danh_muc;
                        this.id_danh_muc_cha_edit = res.data.danhMuc.id_danh_muc_cha;
                        this.is_open_edit = res.data.danhMuc.is_open;
                    } else {
                        toastr.error('Danh mục không tồn tại');
                    }
                })
        },

        acceptUpdate() {
            var payload = {
                'id': this.idEdit,
                'ten_danh_muc': this.ten_danh_muc_edit,
                'id_danh_muc_cha': this.id_danh_muc_cha_edit,
                'is_open': this.is_open_edit,
            };

            // console.log(payload);

            axios
                .post('/admin/danh-muc/update', payload)
                .then((res) => {
                    // console.log(res);
                    toastr.success('Cập thành công danh mục!');
                    this.getData();
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

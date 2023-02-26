new Vue({
    el              : "#app",
    data            : {
        list_vue                :   [],
        idDelete                :   0,
        inputSearch:'',
        donhang :[],
        status:'',
    },

    created(){
        this.getData();
    },

    methods         : {
        getData(){
            axios
                .get('/admin/quan-ly-don-hang/getData')
                .then((res) => {
                    this.list_vue           = res.data.donhang;
                })
        },

        doiTrangThai(id,event) {
            console.log(id)
            console.log(event)
            var payload={
                'value':event,
            }
            axios
                .put('/admin/quan-ly-don-hang/changeStatus/' + id,payload)
                .then((res) => {
                    if(res.data.status) {
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
                'search'    :   this.inputSearch,
            };
            console.log(payload);
            axios
                .post('/admin/quan-ly-don-hang/search', payload)
                .then((res) => {
                    this.list_vue    = res.data.data;
                });
        },

        deleteDanhMuc(id){
            this.idDelete = id;
        },

        acceptDelete(){
            console.log(this.idDelete);
            axios
                .delete('/admin/quan-ly-don-hang/delete/' + this.idDelete)
                .then((res) => {
                    if(res.data.status) {
                        toastr.success('Đã xóa danh mục thành công');
                        this.getData();
                    } else {
                        toastr.error('Danh mục không tồn tại');
                    }
                })
        },

        // seturl(id){
        //     this.url='http://127.0.0.1:8000/admin/quan-ly-don-hang/chi-tiet/'.id;
        //     console.log(this.url);
        // },

        editDanhMuc(id){

            axios
                .get('/admin/quan-ly-don-hang/chi-tiet/' + id)
                .then((res) => {
                    if(res.data.status) {
                        this.don_hang      =   res.data.don_hang;
                        console.log(this.don_hang);
                    } else {
                        toastr.error('Danh mục không tồn tại');
                    }
                })
        },

        acceptUpdate() {
            var payload = {
                'id'                :   this.idEdit,
                'ten_danh_muc'       :   this.ten_danh_muc_edit,
                'id_danh_muc_cha'    :   this.id_danh_muc_cha_edit,
                'is_open'            :   this.is_open_edit,
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
                    $.each(danh_sach_loi, function(key, value){
                        toastr.error(value[0]);
                    });
                });
        },
    },
});

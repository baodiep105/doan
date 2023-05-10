new Vue({
    el              : "#app",
    data            : {
       danh_sach_san_pham   :[],
       danh_sach_mau        :[],
       danh_sach_size       :[],
       danh_sach_chi_tiet   :[],
       idDelete             :0,
       idEdit               :0,
        id_sanpham :'',
        id_mau      :'',
        id_size     :'',
        sl          :'',
        trang_thai  :1,
        idEdit               :0,
        id_sanpham_edit :0,
        id_mau_edit      :0,
        id_size_edit     :0,
        sl_edit          :0,
        trang_thai_edit  :1,
        pagination: {},
        url:[],
        index:0,
        link:'',
    },

    created(){
        this.fetchCustomers();
    },

    methods         : {
        create(e){
            e.preventDefault();
            var payload = {
                'id_sanpham'      :   this.id_sanpham,
                'id_mau'          :   this.id_mau,
                'id_size'   :   this.id_size,
                'sl'           :   this.sl,
                'trang_thai'           :   this.trang_thai,
            };
            axios
                .post('/admin/chi-tiet-san-pham/create', payload )
                .then((res) => {
                    if(res.data.status){
                    toastr.success('Thêm chi tiết sản phẩm mới');
                    this.fetchCustomers( this.link);
                }
                    else{
                    toastr.error('chi tiết sản phẩm đã tồn tại');}
                })
                .catch((res) => {
                    var danh_sach_loi = res.response.data.errors;
                    $.each(danh_sach_loi, function(key, value){
                        toastr.error(value[0]);
                    });
                });
        },
        fetchCustomers(page_url) {
            page_url = page_url || "/admin/chi-tiet-san-pham/getData";
            this.link=page_url;
            console.log(page_url);
            let vm = this;
            let meta = {};
            let link = {};
            axios
                .get(page_url)
                .then((res) => {
                    this.danh_sach_san_pham = res.data.danh_sach_san_pham;
                    this.danh_sach_mau      = res.data.danh_sach_mau;
                    this.danh_sach_size     = res.data.danh_sach_size;
                    this.danh_sach_chi_tiet = res.data.ds_chi_tiet_san_pham.data;
                    this.url=res.data.ds_chi_tiet_san_pham.links;
                    link = {
                        first_page_url: res.data.ds_chi_tiet_san_pham.first_page_url,
                        last_page_url: res.data.ds_chi_tiet_san_pham.last_page_url,
                        next_page_url: res.data.ds_chi_tiet_san_pham.next_page_url,
                        prev_page_ur: res.data.ds_chi_tiet_san_pham.prev_page_url
                    };
                    // console.log(link)
                    meta = {
                        "current_page": res.data.ds_chi_tiet_san_pham.current_page,
                        "from": res.data.ds_chi_tiet_san_pham.from,
                        "last_page": res.data.ds_chi_tiet_san_pham.last_page,
                        "path": res.data.ds_chi_tiet_san_pham.path,
                        "to": res.data.ds_chi_tiet_san_pham.to,
                        "total": res.data.ds_chi_tiet_san_pham.total,
                    }
                    this.index=this.url.length-1;
                    // console.log(this.index);
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

        doiTrangThai(id) {
            console.log(id);
            axios
                .get('/admin/chi-tiet-san-pham/changeStatus/' + id)
                .then((res) => {
                    if(res.data.status) {
                        toastr.success('Đã đổi trạng thái thành công!');
                        // Tình trạng mới là true
                        this.fetchCustomers(this.link);
                    } else {
                        toastr.error('Vui lòng không can thiệp hệ thống!');
                    }
                })
        },

        search() {
            var payload = {
                'search'    :   this.inputSearch,
            };
            axios
                .post('/admin/danh-muc/search', payload)
                .then((res) => {
                    this.danhSachSanPham    = res.data.dataProduct;
                });
        },

        deleteDanhMuc(id){
            this.idDelete = id;
        },

        acceptDelete(){
            axios
                .delete('/admin/chi-tiet-san-pham/delete/' + this.idDelete)
                .then((res) => {
                    if(res.data.status) {
                        toastr.success('Đã xóa chi tiết sản phẩm thành công');
                        this.fetchCustomers(this.link);
                    } else {
                        toastr.error('chi tiết sản phẩm không tồn tại');
                    }
                })
        },

        editDanhMuc(id){
            this.idEdit = id;
            axios
                .get('/admin/chi-tiet-san-pham/edit/' + id)
                .then((res) => {
                    if(res.data.status) {
                        this.idEdit      =   res.data.chi_tiet_san_pham.id;
                        this.id_sanpham_edit   =   res.data.chi_tiet_san_pham.id_sanpham;
                        this.id_mau_edit           =   res.data.chi_tiet_san_pham.id_mau;
                        this.id_size_edit           =   res.data.chi_tiet_san_pham.id_size;
                        this.sl_edit           =   res.data.chi_tiet_san_pham.sl_chi_tiet;
                        this.trang_thai_edit       =res.data.chi_tiet_san_pham.status;
                    } else {
                        toastr.error('Chi tiết không tồn tại');
                    }
                })
        },

        acceptUpdate() {
            var payload = {
                'id'            :   this.idEdit,
                'id_sanpham'    :   this.id_sanpham_edit,
                'id_mau'        :   this.id_mau_edit,
                'id_size'       :   this.id_size_edit,
                'sl'            :   this.sl_edit,
                'trang_thai'    :   this.trang_thai_edit,
            };

            console.log(payload);

            axios
                .post('/admin/chi-tiet-san-pham/update', payload)
                .then((res) => {
                    if(res.data.status){
                    toastr.success('Cập thành công danh mục!');
                    this.fetchCustomers(this.link);
                }
                    else{
                        toastr.error('Chi tiết sản phẩm đã tồn tại')
                    }
                })
                .catch((res) => {
                    var danh_sach_loi = res.response.data.errors;
                    $.each(danh_sach_loi, function(key, value){
                        toastr.error(value[0]);
                    });
                });
        },
        search() {
            var payload = {
                'search'    :   this.inputSearch,
            };
            axios
                .post('/admin/chi-tiet-san-pham/search', payload)
                .then((res) => {
                    this.danh_sach_chi_tiet = res.data.data;
                });
        },
    },
});

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
        value:true,
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
            console.log(this.list_vue);

        },
        updateReply(event){
            this.value=false;
            this.reply=this.child_content;
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
            // console.log($id);
            // let data=null;
            axios
                .get('/admin/quan-ly-danh-gia/get-danh-gia/' + $id)
                .then((res) => {
                    // data=res.data.data;
                    this.id = res.data.data.id;
                    this.content = res.data.data.content;
                    this.email = res.data.data.email;
                    this.child_content=res.data.data.children_content;
                    this.id_san_pham=res.data.data.id_san_pham;
                    console.log(res.data.data.children_content);
                    // console.log(res.data.data);
                })
        },

        getData() {
            axios
                .get('/admin/quan-ly-danh-gia/get-data')
                .then((res) => {
                    this.list_vue = res.data.all;
                    this.list_replied = res.data.reply;
                    this.list_all = res.data.all;
                    this.list_non_reply = res.data.non_rep;

                })
        },
    },
});

<?php

namespace App\Http\Controllers;

use App\Http\Requests\editAnhRequest;
use App\Models\Anh;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class qlanhController extends Controller
{
    public function index()
    {
        return view('admin.SanPham.ql_anh');
    }

    // public function createCategory (Request $request) {
    //     // $validator = $request->validate([

    //     // ]);

    //     $validator =  Validator::make($request->all(),[
    //         'ten_danh_muc'      =>  'required|max:50',
    //         'slug_danh_muc'     =>  'required|max:50',
    //         'id_danh_muc_cha'   =>  'required',
    //         'is_open'           =>  'required|boolean',
    //     ],[
    //         'required'      =>  ':attribute không được để trống',
    //         'max'           =>  ':attribute quá dài',
    //         'exists'        =>  ':attribute không tồn tại',
    //         'boolean'       =>  ':attribute chỉ được chọn True/False',
    //         'unique'        =>  ':attribute đã tồn tại',
    //     ]);

    //     // $rules = [
    //     //     'ten_danh_muc'      =>  'required',
    //     //     'id_danh_muc_cha'   =>  'required',
    //     //     'is_open'           =>  'required|boolean',
    //     // ];
    //     // $input = $request->only('ten_danh_muc','slug_danh_muc','id_danh_muc_cha','is_open');

    //     // $validator = Validator::make($input,$rules);
    //     $random = Str::random(4);
    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'error' => $validator->message()]);
    //     } else {
    //         $postCategory = DanhMucSanPham::create([
    //             'ten_danh_muc' =>$request->ten_danh_muc,
    //             'slug_danh_muc' => Str::slug($request->ten_danh_muc). '-' .$random,
    //             'id_danh_muc_cha' =>$request->id_danh_muc_cha,
    //             'is_open' =>$request->is_open,
    //         ]);
    //     }

    //     if(!empty($postCategory)) {
    //         return response()->json(['sucssec' => 'Thêm thành công!']);
    //     }
    // }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->all());
        $rules = [
            'hinh_anh'  => 'required',
            'id_san_pham'     => 'required',
        ];

        $input     = $request->all();

        $validator = Validator::make($input, $rules, [
            'required'      =>  ':attribute không được để trống',
        ], [
            'hinh_anh' => 'hinh ảnh',
            'id_san_pham' => 'sản phẩm',
        ]);
        $anh = Anh::create([
            'hinh_anh' => $request->hinh_anh,
            'id_san_pham' => $request->id_san_pham,
        ]);

        return response()->json([
            'trangThai'         =>  true,
            'anh' => $anh,
        ]);
    }

    public function getData()
    {

        // $sanPham =DB::table('san_phams')
        //             ->join('hinh_anh','san_phams.id','hinh_anh.id')
        //             ->select('san_phams.ten_san_pham','hinh_anh.hinh_anh')
        //             ->get();
        $sanPham = DB::table('hinh_anh')->join('san_phams', 'hinh_anh.id_san_pham', 'san_phams.id')->select('hinh_anh.*', 'san_phams.ten_san_pham')->orderBy('created_at', 'DESC')->get();
        $data = SanPham::where('is_open', 1)->get();
        return response()->json([
            'sanPham'  => $sanPham,
            'data'     =>  $data,
        ]);
    }


    public function update(editAnhRequest $request)
    {
        // $data     = $request->all();
        $anh = Anh::find($request->id);

        $anh->update([
            'id_san_pham'      =>  $request->id_san_pham,
            'hinh_anh'         => $request->hinh_anh,
        ]);
        return response()->json([
            'status' => true,
            'data'  => $request->all(),
        ]);
    }

    public function edit($id)
    {
        $anh = Anh::find($id);
        if ($anh) {
            return response()->json([
                'status'  =>  true,
                'anh'    =>  $anh,
            ]);
        } else {
            toastr()->error("Ảnh không tồn tại!");
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $anh = Anh::find($id);
        if ($anh) {
            $anh->delete();
            return response()->json([
                'status'  =>  true,
            ]);
        } else {
            return response()->json([
                'status'  =>  false,
            ]);
        }
    }

    public function search(Request $request)
    {
        if ($request->search == "") {
            $data = DB::table('hinh_anh')->join('san_phams', 'hinh_anh.id_san_pham', 'san_phams.id')->select('san_phams.ten_san_pham', 'hinh_anh.hinh_anh')->orderBy('hinh_anh.created_at','DESC')->get();
        } else {
            $data = DB::table('hinh_anh')->join('san_phams', 'hinh_anh.id_san_pham', 'san_phams.id')->where('san_phams.ten_san_pham', 'like', '%' . $request->search . '%')->select('san_phams.ten_san_pham', 'hinh_anh.hinh_anh')->orderBy('hinh_anh.created_at','DESC')->get();
        }
        return response()->json([
            'data' => $data
        ]);
    }
}

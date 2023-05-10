<?php

namespace App\Http\Controllers;

use App\Http\Requests\SanPhamRequest;
use App\Http\Requests\UpdateSanPhamRequest;
use App\Models\ChiTietSanPhamModel;
use App\Models\DanhMucSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SanPhamController extends Controller
{
    public function index()
    {
        if(Auth::guard('users')->user()->id_loai==0){
            return view('admin.san_pham');
        }else if(Auth::guard('users')->user()->id_loai==1){
            return view('nhan_vien.san_pham');
        }

    }

    public function show(){
        return view('admin.SanPham.QL_san_pham');
    }
    public function loadData()
    {
        $danhSachDanhMuc = DanhMucSanPham::all();
        return response()->json([
            'danhSachDanhMuc'   => $danhSachDanhMuc,
        ]);
    }
    public function getData()
    {
        $danhSachSanPham=DB::table('san_phams')
                ->join('danh_muc_san_phams','danh_muc_san_phams.id','=','san_phams.id_danh_muc')
                ->leftJoin('khuyen_mai','san_phams.id','khuyen_mai.id_san_pham')
                ->select('san_phams.*','danh_muc_san_phams.ten_danh_muc',)
                ->selectRaw('round((san_phams.gia_ban-(khuyen_mai.ty_le * san_phams.gia_ban)/100),2) as gia_khuyen_mai')
                ->orderBy('created_at','DESC')
                ->paginate(8);
        $danhSachDanhMuc = DanhMucSanPham::all();
        return response()->json([
            'danhSachSanPham'   => $danhSachSanPham,
            'danhSachDanhMuc'   =>$danhSachDanhMuc,
        ]);
    }
    public function store(SanPhamRequest $request)
    {
        $sanPham = SanPham::create([
            'ten_san_pham'=>$request->ten_san_pham,
            'gia_ban'=>$request->gia_ban  ,
            'brand'=>$request->brand,
            'mo_ta_ngan'=>$request->mo_ta_ngan,
            'mo_ta_chi_tiet'=>$request->mo_ta_chi_tiet,
            'id_danh_muc'=>$request->id_danh_muc,
            'is_open'=>$request->is_open,
        ]);

        return response()->json([
            'status'    => true,
            'sanPham'   => $sanPham,
        ]);
    }
    public function update(UpdateSanPhamRequest $request)
    {
        $sanPham = SanPham::find($request->id);
        $sanPham->update([
            'ten_san_pham'=>$request->ten_san_pham,
            'slug_san_pham'=>Str::slug($request->ten_san_pham),
            'gia_ban'=>$request->gia_ban,
            'brand'=>$request->brand,
            'mo_ta_ngan'=>$request->mo_ta_ngan,
            'mo_ta_chi_tiet'=>$request->mo_ta_chi_tiet,
            'id_danh_muc'=>$request->id_danh_muc,
            'is_open'=>$request->trang_thai,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
    public function edit($id)
    {

        $san_pham = SanPham::find($id);
        if($san_pham) {
            return response()->json([
                'status'  =>  true,
                'san_pham' => $san_pham,
            ]);
        } else {

            return redirect()->back();
        }

    }
    public function delete($id)
    {
        $san_pham = SanPham::find($id);

        if($san_pham) {
            ChiTietSanPhamModel::where('id_sanpham',$id)->delete();
            $san_pham->delete();
            return response()->json(['status' => true]);
        } else {
            return response()->json([
                'status'  =>  false,
            ]);
        }
    }

    public function changeStatus($id)
    {
        $san_pham = SanPham::find($id);
        if($san_pham) {
            // $san_pham->is_open = !$san_pham->is_open;
            if($san_pham->is_open == 1) {
                $san_pham->is_open = 0;
            } else {
                $san_pham->is_open = 1;
            }
            $san_pham->save();
            return response()->json(['status' => true]);
        }
    }

    public function search(Request $request)
    {

        if($request->all()==null){
            $data=DB::table('san_phams')
            ->join('danh_muc_san_phams','san_phams.id_danh_muc','danh_muc_san_phams.id')
            ->select('san_phams.*','danh_muc_san_phams.ten_danh_muc')
            ->get();
        }
        else{
            $data=DB::table('san_phams')
            ->join('danh_muc_san_phams','san_phams.id_danh_muc','danh_muc_san_phams.id')
            ->where('ten_san_pham', 'like', '%' . $request->search .'%')
            ->orwhere('ten_danh_muc', 'like', '%' . $request->search .'%')
            ->orwhere('brand', 'like', '%' . $request->search .'%')
            ->select('san_phams.*','danh_muc_san_phams.ten_danh_muc')
            ->get();
        }
        return response()->json(['dataProduct' => $data]);
    }

}

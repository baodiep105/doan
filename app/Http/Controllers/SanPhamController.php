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
        if (Auth::guard('users')->user()->id_loai == 0) {
            return view('admin.san_pham');
        } else if (Auth::guard('users')->user()->id_loai == 1) {
            return view('nhan_vien.san_pham');
        }
    }

    public function show()
    {
        return view('admin.SanPham.QL_san_pham');
    }

    public function loadData()
    {
        $danhSachDanhMuc = DanhMucSanPham::where('is_open', 1)->orderBy('created_at', 'DESC')->get();
        return response()->json([
            'danhSachDanhMuc'   => $danhSachDanhMuc,
        ]);
    }

    public function getData()
    {
        $danhSachSanPham = DB::table('san_phams')
            ->join('danh_muc_san_phams', 'danh_muc_san_phams.id', '=', 'san_phams.id_danh_muc')
            ->leftJoin('khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham')
            ->select('san_phams.*', 'danh_muc_san_phams.ten_danh_muc',)
            ->selectRaw('round((san_phams.gia_ban-(khuyen_mai.ty_le * san_phams.gia_ban)/100),2) as gia_khuyen_mai')
            ->orderBy('created_at', 'DESC')
            ->paginate(8);
        return response()->json([
            'danhSachSanPham'   => $danhSachSanPham,
        ]);
    }

    public function store(SanPhamRequest $request)
    {
        $sanPham = SanPham::create([
            'ten_san_pham' => $request->ten_san_pham,
            'gia_ban' => $request->gia_ban,
            'brand' => $request->brand,
            'mo_ta_ngan' => $request->mo_ta_ngan,
            'mo_ta_chi_tiet' => $request->mo_ta_chi_tiet,
            'id_danh_muc' => $request->id_danh_muc,
            'is_open' => $request->is_open,
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
            'ten_san_pham' => $request->ten_san_pham,
            'slug_san_pham' => Str::slug($request->ten_san_pham),
            'gia_ban' => $request->gia_ban,
            'brand' => $request->brand,
            'mo_ta_ngan' => $request->mo_ta_ngan,
            'mo_ta_chi_tiet' => $request->mo_ta_chi_tiet,
            'id_danh_muc' => $request->id_danh_muc,
            'is_open' => $request->trang_thai,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function edit($id)
    {
        if (!empty($id)) {
            $san_pham = SanPham::find($id);
            if ($san_pham) {
                return response()->json([
                    'status'  =>  true,
                    'san_pham' => $san_pham,
                ]);
            } else {
                toastr()->error('Không tìm thấy sản phẩm cần sửa');
                return redirect()->back();
            }
        } else {
            toastr()->error('Cập nhật sản phẩm không thành công');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $san_pham = SanPham::find($id);
            if (!$san_pham) {
                toastr()->error('Sản phẩm không tồn tại!');
                return redirect()->back();
            } else {
                DB::table('hinh_anh')->join('san_phams','hinh_anh.id_san_pham','san_phams.id')
                    ->where('san_phams.id',$id)->delete();
                DB::table('chi_tiet_san_pham')->join('san_phams','chi_tiet_san_pham.id_sanpham','san_phams.id')
                    ->where('san_phams.id',$id)->delete();
                DB::table('khuyen_mai')->join('san_phams','khuyen_mai.id_san_pham','san_phams.id')
                ->where('san_phams.id',$id)->delete();
                DB::table('san_phams')->where('id',$id)->delete();
                return response()->json(['status' => true]);
            }
        } else {
            toastr()->error('Xóa sản phẩm không thành công');
            return redirect()->back();
        }
    }

    public function changeStatus($id)
    {
        if (!empty($id)) {
            $san_pham = SanPham::find($id);
            if (!$san_pham) {
                return response()->json(['status' => false]);
            }else{
                if ($san_pham->is_open == 1) {
                    $san_pham->is_open = 0;
                } else {
                    $san_pham->is_open = 1;
                }
                $san_pham->save();
                return response()->json(['status' => true]);

            }
        }
    }

    public function search(Request $request)
    {
        if (is_null($request->all())) {
            $data = DB::table('san_phams')
                ->join('danh_muc_san_phams', 'san_phams.id_danh_muc', 'danh_muc_san_phams.id')
                ->select('san_phams.*', 'danh_muc_san_phams.ten_danh_muc')
                ->orderBy('san_phams.created_at', 'DESC')
                ->get();
        } else {
            $data = DB::table('san_phams')
                ->join('danh_muc_san_phams', 'san_phams.id_danh_muc', 'danh_muc_san_phams.id')
                ->where('ten_san_pham', 'like', '%' . $request->search . '%')
                ->orwhere('ten_danh_muc', 'like', '%' . $request->search . '%')
                ->orwhere('brand', 'like', '%' . $request->search . '%')
                ->select('san_phams.*', 'danh_muc_san_phams.ten_danh_muc')
                ->orderBy('san_phams.created_at', 'DESC')
                ->get();
        }
        return response()->json(['dataProduct' => $data]);
    }
}

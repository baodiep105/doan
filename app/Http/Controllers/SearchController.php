<?php

namespace App\Http\Controllers;

use App\Http\Requests\request as RequestsRequest;
use App\Models\ChiTietSanPhamModel;
use App\Models\DanhMucSanPham;
use App\Models\MauSacModel;
use App\Models\SanPham;
use App\Models\sizeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

class SearchController extends Controller
{
    public function dataProduct()
    {
        $dataProduct = SanPham::where('is_open', 1)
            ->select('id as id_sanpham', 'ten_san_pham', 'gia_ban', 'brand', 'mo_ta_ngan', 'mo_ta_chi_tiet', 'id_danh_muc', 'is_open')->paginate(8);
        $dataProduct = $this->getAnh($dataProduct);
        foreach ($dataProduct as $value) {
            $a = ChiTietSanPhamModel::where('id_sanpham', $value->id_sanpham)->sum('sl_chi_tiet');
            $value->so_luong = (int)$a;
        }
        $mauSac = MauSacModel::all();
        $size = sizeModel::all();
        $category = DanhMucSanPham::all();
        return response()->json([
            'product'  => $dataProduct,
            'size'  => $size,
            'mauSac' => $mauSac,
            'category' => $category,
        ]);
    }

    public function search(Request $request)
    {
        if (empty($request->search)) {
            $data = DB::table('san_phams')->leftjoin('khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham')->select('san_phams.*', 'khuyen_mai.ty_le as khuyen_mai')->where('san_phams.is_open', 1)->paginate(8);
        } else {
            $data = DB::table('san_phams')
                ->leftjoin('khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham')
                ->leftjoin('danh_muc_san_phams', 'san_phams.id_danh_muc', 'danh_muc_san_phams.id')
                ->where('san_phams.is_open', 1)
                ->where('san_phams.ten_san_pham', 'like', '%' .  $request->search . '%')
                ->orWhere('danh_muc_san_phams.ten_danh_muc', 'like', '%' .  $request->search . '%')
                ->orWhere('san_phams.brand', 'like', '%' .  $request->search . '%')
                ->select('san_phams.id as id_sanpham', 'san_phams.ten_san_pham', 'san_phams.gia_ban', 'san_phams.brand', 'san_phams.is_open', 'khuyen_mai.ty_le as khuyen_mai', 'danh_muc_san_phams.ten_danh_muc')
                ->paginate(8);
        }
        $data = $this->getAnh($data);
        foreach ($data as $value) {
            $a = ChiTietSanPhamModel::where('id_sanpham', $value->id_sanpham)->sum('sl_chi_tiet');
            $value->so_luong = (int)$a;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
}

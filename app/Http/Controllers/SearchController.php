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
        $dataProduct = SanPham::where('is_open', 1)->paginate(8);
        $hinh_anh = DB::table('hinh_anh')->get();
        $anh = array();
        foreach ($dataProduct as $key)
            foreach ($hinh_anh as $value) {
                if ($key->id == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }
        foreach ($dataProduct as $value) {
            $a=ChiTietSanPhamModel::where('id_sanpham',$value->id)->sum('sl_chi_tiet');
            $value->so_luong=(int)$a;
            foreach ($anh as $key) {
                if ($value->id == $key->id_san_pham) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }
        // $anh = DB::table('hinh_anh')->where('id_san_pham', $id)->get();
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
        if (!$request->search) {
            $data = DB::table('san_phams')->leftjoin('khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham')->select('san_phams.*','khuyen_mai.ty_le as khuyen_mai')->where('san_phams.is_open', 1)->paginate(8);
        } else {
            $data = DB::table('san_phams')
                ->leftjoin('khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham')
                ->leftjoin('danh_muc_san_phams', 'san_phams.id_danh_muc', 'danh_muc_san_phams.id')
                ->where('san_phams.is_open',1)
                ->where('san_phams.ten_san_pham', 'like', '%' .  $request->search . '%')
                ->orWhere('danh_muc_san_phams.ten_danh_muc', 'like', '%' .  $request->search . '%')
                ->orWhere('san_phams.brand', 'like', '%' .  $request->search . '%')
                ->select('san_phams.*','khuyen_mai.ty_le as khuyen_mai ', 'danh_muc_san_phams.ten_danh_muc')
                ->paginate(8);
        }
        $hinh_anh = DB::table('hinh_anh')->get();
        $anh = array();
        foreach ($data as $key)
            foreach ($hinh_anh as $value) {
                if ($key->id == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }
        foreach ($data as $value) {
            $a=ChiTietSanPhamModel::where('id_sanpham',$value->id)->sum('sl_chi_tiet');
            $value->so_luong=(int)$a;
            foreach ($anh as $key) {
                if ($value->id == $key->id_san_pham) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

}

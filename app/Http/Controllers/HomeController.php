<?php

namespace App\Http\Controllers;

use App\Http\Requests\DanhMucSanPham;
use App\Http\Requests\request as RequestsRequest;
use App\Models\Banner;
use App\Models\DanhMucSanPham as ModelsDanhMucSanPham;
use App\Models\MauSacModel;
use App\Models\ChiTietSanPhamModel;
use App\Models\SanPham;
use App\Models\sanphamyeuthichmodel;
use App\Models\sizeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SanPhamYeuThich;
use Cookie;
use Config;
use stdClass;
use Google\Client as GoogleClient;

class HomeController extends Controller
{
    public function BestSell()
    {
        $best_sell = DB::table('chi_tiet_don_hangs')
            ->join('chi_tiet_san_pham', 'chi_tiet_don_hangs.id_chi_tiet_san_pham', 'chi_tiet_san_pham.id')
            ->rightJoin('san_phams', 'chi_tiet_san_pham.id_sanpham', 'san_phams.id')
            // ->leftJoin('khuyen_mai','san_phams.id','khuyen_mai.id_san_pham')
            ->select('chi_tiet_san_pham.id_sanpham')
            ->select('san_phams.ten_san_pham', 'san_phams.id as id_sanpham', 'san_phams.gia_ban', 'san_phams.gia_ban', 'san_phams.mo_ta_ngan', 'san_phams.mo_ta_chi_tiet', 'san_phams.id_danh_muc', 'san_phams.is_open', 'san_phams.brand')
            ->selectRaw('count(chi_tiet_san_pham.id_sanpham) as luot_mua_hang')
            ->groupBy('chi_tiet_san_pham.id_sanpham', 'san_phams.ten_san_pham', 'san_phams.id', 'san_phams.gia_ban', 'san_phams.gia_ban', 'san_phams.mo_ta_ngan', 'san_phams.mo_ta_chi_tiet', 'san_phams.id_danh_muc', 'san_phams.is_open', 'san_phams.brand')
            ->orderBy('so_luong', 'desc')
            ->take(8)
            ->get();
        $sanPham = $this->getAnh($best_sell);
        foreach ($sanPham as $value) {
            $a = ChiTietSanPhamModel::where('id_sanpham', $value->id_sanpham)->sum('sl_chi_tiet');
            $value->so_luong = (int)$a;
        }
        return response()->json([
            'status' => 'success',
            'data' => $best_sell,
        ]);
    }
    public function arrival()
    {
        $sanPham = DB::table('san_phams')->leftjoin('khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham')->where('san_phams.is_open', 1)->where(function ($query) {
            $query->where('san_phams.created_at', '>', Carbon::now()->subDay(365));
            $query->orwhere('san_phams.created_at', '=', Carbon::now()->subDay(365));
        })->orderBy('san_phams.created_at', 'DESC')->take(8)->select('san_phams.id as id_sanpham', 'san_phams.ten_san_pham', 'san_phams.gia_ban', 'san_phams.brand', 'san_phams.is_open', 'khuyen_mai.ty_le as khuyen_mai')->get();
        $sanPham = $this->getAnh($sanPham);
        foreach ($sanPham as $value) {
            $a = ChiTietSanPhamModel::where('id_sanpham', $value->id_sanpham)->sum('sl_chi_tiet');
            $value->so_luong = (int)$a;
        }


        return response()->json([
            'status' => 'success',
            'sanPham' => $sanPham,
        ]);
    }
    public function danhMuc()
    {
        $data = ModelsDanhMucSanPham::where('is_open', 1)->get();
        return response()->json([
            'status' => 'success',
            'data'  => $data,
        ]);
    }
    public function product()
    {
        $sanPham = DB::table('san_phams')->leftjoin('khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham')
            ->orderBy('san_phams.created_at', 'DESC')
            ->select('san_phams.id as id_sanpham', 'san_phams.ten_san_pham', 'san_phams.gia_ban', 'san_phams.brand', 'san_phams.is_open', 'khuyen_mai.ty_le as khuyen_mai')
            ->take(8)->get();
        $sanPham = $this->getAnh($sanPham);
        foreach ($sanPham as $value) {
            $a = ChiTietSanPhamModel::where('id_sanpham', $value->id_sanpham)->sum('sl_chi_tiet');
            $value->so_luong = (int)$a;
        }
        dd($sanPham);
        return response()->json([
            'status' => 'success',
            'product' => $sanPham,
        ]);
    }

    public function banner()
    {
        $banner = Banner::where('is_open', 1)->select('banner_1', 'banner_2', 'banner_3', 'banner_4', 'banner_5')->get();
        return response()->json([
            'status' => 'success',
            'data' => $banner,
        ]);
    }
}

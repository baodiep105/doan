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
    public function BestSell(){
        $best_sell=DB::table( 'chi_tiet_don_hangs' )
        ->join( 'chi_tiet_san_pham', 'chi_tiet_don_hangs.id_chi_tiet_san_pham', 'chi_tiet_san_pham.id' )
        ->rightJoin( 'san_phams', 'chi_tiet_san_pham.id_sanpham', 'san_phams.id' )
        // ->leftJoin('khuyen_mai','san_phams.id','khuyen_mai.id_san_pham')
        ->select( 'chi_tiet_san_pham.id_sanpham' )
        ->select( 'san_phams.ten_san_pham','san_phams.id','san_phams.gia_ban','san_phams.gia_ban','san_phams.mo_ta_ngan','san_phams.mo_ta_chi_tiet','san_phams.id_danh_muc','san_phams.is_open','san_phams.brand')
        ->selectRaw( 'count(chi_tiet_san_pham.id_sanpham) as luot_mua_hang' )
        ->groupBy( 'chi_tiet_san_pham.id_sanpham', 'san_phams.ten_san_pham','san_phams.id','san_phams.gia_ban','san_phams.gia_ban','san_phams.mo_ta_ngan','san_phams.mo_ta_chi_tiet','san_phams.id_danh_muc','san_phams.is_open','san_phams.brand')
        ->orderBy( 'so_luong', 'desc' )
        ->take( 8 )
        ->get();
        $hinh_anh = DB::table('hinh_anh')->get();
        $anh = array();
        foreach ($best_sell as $key)
            foreach ($hinh_anh as $value) {
                if ($key->id == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }
        foreach ($best_sell as $value) {
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
            'status'=>'success',
            'data'=>$best_sell,
        ]);

    }
    public function arrival()
    {
        $sanPham =DB::table('san_phams')->leftjoin('khuyen_mai','san_phams.id','khuyen_mai.id_san_pham')->where('san_phams.is_open',1)->where(function($query){
            $query->where('san_phams.created_at', '>', Carbon::now()->subDay(365));
            $query->orwhere('san_phams.created_at', '=', Carbon::now()->subDay(365));
        })->orderBy('created_at','DESC')->take(8)->select('san_phams.*','khuyen_mai.ty_le as khuyen_mai')->get();
        $hinh_anh = DB::table('hinh_anh')->get();
        $anh = array();
        foreach ($sanPham as $key)
            foreach ($hinh_anh as $value) {
                if ($key->id == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }
        foreach ($sanPham as $value) {
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
            'status' => 'success',
            'sanPham' => $sanPham,
        ]);
    }
    public function danhMuc()
    {
        $data = ModelsDanhMucSanPham::where('is_open',1)->get();
        return response()->json([
            'status' => 'success',
            'data'  => $data,
        ]);
    }
    public function product()
    {
        $sanPham =DB::table('san_phams')->leftjoin('khuyen_mai','san_phams.id','khuyen_mai.id_san_pham')
                                        ->orderBy('created_at','DESC')
                                        ->select('san_phams.*','khuyen_mai.ty_le as khuyen_mai')
                                        ->take(8)->get();
        $hinh_anh = DB::table('hinh_anh')->get();
        $anh = array();
        foreach ($sanPham as $key)
            foreach ($hinh_anh as $value) {
                if ($key->id == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }
        foreach ($sanPham as $value) {
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
            'status' => 'success',
            'product' => $sanPham,
        ]);
    }

    public function yeuthich(Request $request)
    {
        $exist = sanphamyeuthichmodel::select('*')
            ->where('id_san_pham', $request->id_san_pham)
            ->where('id_user', auth()->user()->id)
            ->exists();
        if ($exist) {
            return response()->json([
                'status' => 'erorr',
                'erorr' => 'the same key',
            ]);
        }
        $yeuthich = sanphamyeuthichmodel::create([
            'id_user' => auth()->user()->id,
            'id_san_pham' => $request->id_san_pham,
        ]);
        return response()->json([
            'status' => 'success',
            'yeuthich' => $yeuthich,
        ]);
    }
    public function yeu()
    {
        $data = DB::table('san_pham_yeu_thich')->join('san_phams', 'san_pham_yeu_thich.id_san_pham', 'san_phams.id')
            ->where('id_user', auth()->user()->id)
            ->select('san_phams.*', 'san_pham_yeu_thich.id as id_yeu_thich')
            ->get();

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
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function deleteYeu($id)
    {
        $yeuthich = sanphamyeuthichmodel::find($id);
        if ($yeuthich) {
            $yeuthich->delete();
            return response()->json([
                'status'  =>  'success',
            ]);
        }
        return response()->json([
            'status' => 'erorr',
        ]);
    }

    public function banner(){
        $banner=Banner::where('is_open',1)->select('banner_1','banner_2','banner_3','banner_4','banner_5')->get();
        return response()->json([
            'status'=>'success',
            'data'=>$banner,
        ]);
    }
}

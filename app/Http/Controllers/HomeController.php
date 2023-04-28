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
    public function arrival()
    {
        $sanPham =DB::table('san_phams')->leftjoin('khuyen_mai','san_phams.id','khuyen_mai.id_san_pham')->where('san_phams.is_open',1)->where(function($query){
            $query->where('san_phams.created_at', '>', Carbon::now()->subDay(365));
            $query->orwhere('san_phams.created_at', '=', Carbon::now()->subDay(365));
        })->orderBy('created_at','DESC')->take(8)->select('san_phams.*','khuyen_mai.ty_le as khuyen_mai')->get();
        $hinh_anh = DB::table('hinh_anh')->get();
        $id = array();
        foreach ($sanPham as $value) {
            array_push($id, $value->id);
        }
        $anh = array();
        foreach ($id as $key)
            foreach ($hinh_anh as $value) {
                if ($key == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }

        foreach ($sanPham as $value) {
            foreach ($anh as $key) {
                if ($value->id == $key->id_san_pham) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }
        return response()->json([
            'sanPham' => $sanPham,
        ]);
    }
    public function danhMuc()
    {
        $data = ModelsDanhMucSanPham::all();
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
        $id = array();
        foreach ($sanPham as $value) {
            array_push($id, $value->id);
        }
        $anh = array();
        foreach ($id as $key)
            foreach ($hinh_anh as $value) {
                if ($key == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }

        foreach ($sanPham as $value) {
            foreach ($anh as $key) {
                if ($value->id == $key->id_san_pham) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }
        return response()->json([
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

    // public function test(){
    //     session_start();
    //     $a=new stdClass;
    //     $a->email= "hoangthanhwork123@gmail.com";
    //     $a->tong_tien= 81.26;
    //     $a->thuc_tra= 81.26;
    //     $a->tien_giam= 0;
    //     $a->dia_chi= "Thành phố Hà Nội - Quận Hoàn Kiếm -  - 12343";
    //     $a->nguoi_nhan= "Quang Nguyễn";
    //     $a->sdt= "0772531816";
    //     $a->ghi_chu= "rẻwe";
    //     // dd($a);
    //     // $cookie_name='a';
    //     // $cookie_value='b';
    //     // setcookie($cookie_name, $cookie_value, time() + (86400 * 30),'/');
    //     // setcookie("asd","", time()-(86400 * 30));
    //     // // setcookie('asd', 'adsasd', time() + (86400 * 30), "/");
    //     //     // setcookie('bao','adsads',time()+ (86400 * 30));
    //     // dd($_COOKIE);
    //     // dd( Config::get('global_a.global'));
    // }
    // public function test_session(){
    //     // session_start();
    // //    dd($_SESSION['a']);
    //    dd(Config::get('services.facebook'));
    // }
    public function yeu()
    {
        $data = DB::table('san_pham_yeu_thich')->join('san_phams', 'san_pham_yeu_thich.id_san_pham', 'san_phams.id')
            ->where('id_user', auth()->user()->id)
            ->select('san_phams.*', 'san_pham_yeu_thich.id as id_yeu_thich')
            ->get();

        $hinh_anh = DB::table('hinh_anh')->get();
        $id = array();
        foreach ($data as $value) {
            array_push($id, $value->id);
        }
        $anh = array();
        foreach ($id as $key)
            foreach ($hinh_anh as $value) {
                if ($key == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }
        // return response()->json([
        //     'anh'=>$dat,
        // ]);
        foreach ($data as $value) {
            $a=ChiTietSanPhamModel::where('id_sanpham',$value->id)->sum('sl_chi_tiet');
            $value->tinh_trang=($a==0)?0:1;
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

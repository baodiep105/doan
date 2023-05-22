<?php

namespace App\Http\Controllers;

use App\Http\Requests\request as RequestsRequest;
use App\Models\DanhGia;
use App\Models\DanhMucSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Switch_;
use App\Models\ChiTietSanPhamModel;

class filterController extends Controller {
    public function dataProduct() {
        $brand = SanPham::select( 'brand' )->groupBy( 'brand' )->get();
        $gia = [ '1000000-5000000', '5000000-15000000', '15000000-20000000' ];
        $danhmuc = DanhMucSanPham::all();
        return response()->json( [
            'status'  =>'success',
            'brand'         =>$brand,
            'danh_muc'=>$danhmuc,
            'khoang_gia'=>$gia,
        ] );
    }

    public function filter(Request $request) {
        if ( !is_null($request->id_danh_muc) && !is_null($request->brand) && !is_null($request->cost) ) {
            // dd(1);
            switch ( $request->cost) {
                case 0:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->where( 'san_phams.brand', 'like', '%' . $request->brand . '%' )->whereBetween( 'san_phams.gia_ban', [ 1000000, 5000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                break;
                case 1:
                    $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                        ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->where( 'san_phams.brand', 'like', '%' . $request->brand . '%' )->whereBetween( 'san_phams.gia_ban', [ 5000000, 15000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                    break;
                case 2:
                    $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                        ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->where( 'san_phams.brand', 'like', '%' . $request->brand . '%' )->whereBetween( 'san_phams.gia_ban', [ 15000000, 20000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                    break;
            }
        } else if ( !is_null($request->id_danh_muc) && !is_null($request->brand) && is_null($request->cost) ) {

            $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
            ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->where( 'san_phams.brand', 'like', '%' . $request->brand . '%' )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );

        } else if ( !is_null($request->id_danh_muc) && is_null($request->brand ) && is_null($request->cost) ) {
            $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
            ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
        } else  if ( !is_null($request->id_danh_muc) && is_null($request->brand ) && !is_null($request->cost) ) {

            switch ( $request->cost) {
                case 0:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->whereBetween( 'san_phams.gia_ban', [ 1000000, 5000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                break;
                case 1:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->whereBetween( 'san_phams.gia_ban', [ 5000000, 15000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                break;
                case 2:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->where( 'san_phams.id_danh_muc', $request->id_danh_muc )->whereBetween( 'san_phams.gia_ban', [ 15000000, 20000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                break;
            }
        } else  if ( is_null($request->id_danh_muc) && is_null($request->brand ) && is_null($request->cost) ) {
            $sanpham = SanPham::where( 'san_phams.is_open', 1 )->paginate( 8 );
        } else  if ( is_null($request->id_danh_muc) && !is_null($request->brand) && !is_null($request->cost) ) {
            switch ( $request->cost) {
                case 0:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->where( 'brand', $request->brand )->whereBetween( 'gia_ban', [ 1000000, 5000000 ] )->select( 'san_phams.*' )->paginate( 8 );
                break;
                case 1:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->where( 'brand', $request->brand )->whereBetween( 'gia_ban', [ 5000000, 15000000 ] )->select( 'san_phams.*' )->paginate( 8 );
                break;
                case 2:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->where( 'brand', $request->brand )->whereBetween( 'gia_ban', [ 15000000, 20000000 ] )->select( 'san_phams.*' )->paginate( 8 );
                break;
            }
        } else if ( is_null($request->id_danh_muc)  && is_null($request->brand)  && !is_null($request->cost) ) {
            switch ( $request->cost) {
                case 0:
                    // dd(1);
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->whereBetween( 'san_phams.gia_ban', [ 1000000, 5000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                break;
                case 1:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->whereBetween( 'san_phams.gia_ban', [ 5000000, 15000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );

                break;
                case 2:
                $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
                ->where( 'san_phams.is_open', 1 )->whereBetween( 'san_phams.gia_ban', [ 15000000, 20000000 ] )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
                break;
            }
        } else {
            $sanpham = DB::table( 'san_phams' )->leftJoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
            ->where( 'san_phams.is_open', 1 )->where( 'san_phams.brand', 'like', '%' . $request->brand . '%' )->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->paginate( 8 );
        }
        $hinh_anh = DB::table('hinh_anh')->get();
        $anh = array();
        foreach ($sanpham as $key)
            foreach ($hinh_anh as $value) {
                if ($key->id == $value->id_san_pham) {
                    array_push($anh, $value);
                    break;
                }
            }
        foreach ($sanpham as $value) {
            $a=ChiTietSanPhamModel::where('id_sanpham',$value->id)->sum('sl_chi_tiet');
            $value->so_luong=(int)$a;
            foreach ($anh as $key) {
                if ($value->id == $key->id_san_pham) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }
        return response()->json( [
            'stauts'=>'success',
            'san_pham' => $sanpham,
        ] );
    }
}

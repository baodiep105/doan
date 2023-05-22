<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use Carbon\Carbon;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NunoMaduro\Collision\Adapters\Phpunit\Style;
use stdClass;

class thongKeController extends Controller {


    public function thongke() {
        $doanhThu = ( int )DonHang::whereYear( 'created_at', Carbon::now()->year )->sum( 'thuc_tra' );
        $sanPham = DB::table( 'chi_tiet_don_hangs' )
                ->join( 'chi_tiet_san_pham', 'chi_tiet_don_hangs.id_chi_tiet_san_pham', 'chi_tiet_san_pham.id' )
                ->join( 'san_phams', 'chi_tiet_san_pham.id_sanpham', 'san_phams.id' )
                ->select( 'chi_tiet_san_pham.id_sanpham' )
                ->select( 'san_phams.ten_san_pham' )
                ->selectRaw( 'sum(chi_tiet_don_hangs.so_luong) as so_luong' )
                ->groupBy( 'chi_tiet_san_pham.id_sanpham', 'san_phams.ten_san_pham' )
                ->orderBy( 'so_luong', 'desc' )
                ->whereYear( 'chi_tiet_don_hangs.created_at', Carbon::now()->year )
                ->take( 10 )
                ->get();
                // dd($sanPham);
                // dd($alldonhang);
        $khuyenMai = KhuyenMai::where( 'is_open', 1 )->count();
        $khachHang = DB::table( 'don_hangs' )->select( 'email' )
                        ->selectRaw( 'sum(don_hangs.thuc_tra) as so_luong' )
                        ->groupBy( 'email' )
                        ->orderBy( 'so_luong', 'desc' )
                        ->take( 10 )
                        ->get();
        $alldonhang = ChiTietDonHang::sum('so_luong');
        $allKhachHang=DonHang::selectRaw( 'sum(don_hangs.thuc_tra) as so_luong' )->get();
        // dd($allKhachHang);
        $all = SanPham::where( 'is_open', 1 )->count();
        $tyLeKhachHang = round( ( $khachHang[ 0 ]->so_luong / $allKhachHang[0]->so_luong * 100 ), 2 );
        $tyLe = round( ( ( $sanPham[ 0 ]->so_luong / $alldonhang ) * 100 ), 2 );
        $danhMuc = DB::table( 'chi_tiet_don_hangs' )
                    ->join( 'chi_tiet_san_pham as ctsp', 'chi_tiet_don_hangs.id_chi_tiet_san_pham', 'ctsp.id' )
                    ->join( 'san_phams as sp', 'ctsp.id_sanpham', 'sp.id' )
                    ->join( 'danh_muc_san_phams as dm', 'sp.id_danh_muc', 'dm.id' )
                    ->join( 'don_hangs as dh', 'chi_tiet_don_hangs.don_hang_id', 'dh.id' )
                    ->select( 'dm.id', 'dm.ten_danh_muc' )
                    ->selectRaw( 'sum(chi_tiet_don_hangs.so_luong) as so_luong' )
                    ->groupBy( 'dm.id', 'dm.ten_danh_muc' )
                    ->orderBy( 'so_luong', 'desc' )
                    ->get();
        $allDanhMuc=0;
        foreach($danhMuc as $value){
            $allDanhMuc+=$value->so_luong;
        }
        $tyLeDanhMuc=round( ( ($danhMuc[0]->so_luong / $allDanhMuc ) * 100 ), 2 );
        return view( 'admin.thong_ke', compact( 'doanhThu', 'sanPham', 'khuyenMai', 'khachHang', 'all', 'alldonhang', 'tyLe', 'tyLeKhachHang','tyLeDanhMuc','danhMuc' ) );
    }

    public function DoanhThuchart() {
        $data = array();
        for ( $i = 1; $i <= 12; $i++ ) {
            $donHang = DonHang::whereYear( 'created_at', Carbon::now()->year )->whereMonth( 'created_at', $i )->sum( 'thuc_tra' );
            array_push( $data, $donHang );
        }
        return response()->json( [
            'status' => 'success',
            'data' => $data,
        ] );
    }

    public function Customnerchart() {
        $khachHang = DB::table( 'don_hangs' )->select( 'email' )
        ->selectRaw( 'count(email) as so_luong' )
        ->groupBy( 'email' )
        ->orderBy( 'so_luong', 'desc' )
        ->take( 5 )
        ->get();
        $tong = DonHang::count();

        $email = array();
        $tyLe = array();
        $con_lai = 100;
        foreach ( $khachHang as $key ) {
            array_push( $email, $key->email );
            array_push( $tyLe, round( ( $key->so_luong / $tong * 100 ), 2 ) );
            $con_lai -= round( ( $key->so_luong / $tong * 100 ), 2 );
        }
        if ( $khachHang->count() < 5 ) {
            return response()->json( [
                'status' => 'success',
                'email' => $email,
                'tyle' => $tyLe,
            ] );
        } else {
            array_push( $email, 'còn lại' );
            array_push( $tyLe, $con_lai );
            return response()->json( [
                'status' => 'success',
                'email' => $email,
                'tyle' => $tyLe,
            ] );
        }
    }

    public function ProductChart() {
        $sanPham = DB::table( 'chi_tiet_don_hangs' )
        ->join( 'chi_tiet_san_pham', 'chi_tiet_don_hangs.id_chi_tiet_san_pham', 'chi_tiet_san_pham.id' )
        ->join( 'san_phams', 'chi_tiet_san_pham.id_sanpham', 'san_phams.id' )
        ->select( 'chi_tiet_san_pham.id_sanpham' )
        ->select( 'san_phams.ten_san_pham' )
        ->selectRaw( 'sum(chi_tiet_don_hangs.so_luong) as so_luong' )
        ->whereYear( 'chi_tiet_don_hangs.created_at', Carbon::now()->year )
        ->groupBy( 'chi_tiet_san_pham.id_sanpham', 'san_phams.ten_san_pham' )
        ->orderBy( 'so_luong', 'desc' )
        ->take( 5 )
        ->get();
        // dd( $sanPham );
        $tong = ChiTietDonHang::whereYear( 'created_at', Carbon::now()->year )->sum('so_luong');
        $con_lai = 100;
        $products = array();
        $tyles = array();
        foreach ( $sanPham as $value ) {
            array_push( $products, $value->ten_san_pham );
            // dd( $value->so_luong );
            array_push( $tyles, round( ( ( $value->so_luong ) / $tong ) * 100, 2 ) );
            $con_lai -= round( ( ( $value->so_luong ) / $tong ) * 100, 2 );
        }
        if ( $sanPham->count() < 5 ) {
            return response()->json( [
                'status' => 'success',
                'product' => $products,
                'tyle' => $tyles
            ] );
        } else {
            array_push( $products, 'còn lại' );
            array_push( $tyles, $con_lai );
            return response()->json( [
                'status' => 'success',
                'product' => $products,
                'tyle' => $tyles
            ] );
        }
    }
}

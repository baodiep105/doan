<?php

namespace App\Http\Controllers;

use App\Models\ChiTietSanPhamModel;
use App\Models\MauSacModel;
use App\Models\SanPham;
use App\Models\sizeModel;
use Illuminate\Http\Request;
use App\Http\Requests\ChiTietSanPhamRequest;
use App\Http\Requests\editchitietsanphamrequest;
use App\Http\Requests\editDanhMucRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

class ChiTietSanPhamController extends Controller {
    public function index() {
        return view( 'admin.SanPham.chi_tiet_san_pham' );
    }

    public function getdata() {
        $ds_chi_tiet_san_pham = DB::table( 'chi_tiet_san_pham' )
        ->join( 'size', 'chi_tiet_san_pham.id_size', '=', 'size.id' )
        ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', '=', 'mau_sac.id' )
        ->join( 'san_phams', 'chi_tiet_san_pham.id_sanpham', '=', 'san_phams.id' )
        ->select( 'chi_tiet_san_pham.id', 'chi_tiet_san_pham.sl_chi_tiet', 'chi_tiet_san_pham.status', 'san_phams.ten_san_pham', 'mau_sac.ten_mau', 'size.size' )
        ->paginate( 6 );
        $danh_sach_mau = MauSacModel::all();
        $danh_sach_san_pham = SanPham::all();
        $danh_sach_size = sizeModel::all();
        return response()->json( [
            'danh_sach_mau' => $danh_sach_mau,
            'danh_sach_san_pham' => $danh_sach_san_pham,
            'danh_sach_size' => $danh_sach_size,
            'ds_chi_tiet_san_pham' => $ds_chi_tiet_san_pham,
        ] );
    }

    public function create( ChiTietSanPhamRequest $request ) {
        $exist = ChiTietSanPhamModel::select( '*' )
        ->where( 'id_sanpham', $request->id_sanpham )
        ->where( 'id_mau', $request->id_mau, )
        ->where( 'id_size', $request->id_size )
        ->exists();
        if ( !$exist ) {
            $chi_tiet_san_pham = ChiTietSanPhamModel::create( [
                'id_sanpham' => $request->id_sanpham,
                'id_mau' => $request->id_mau,
                'id_size' => $request->id_size,
                'sl_chi_tiet' => $request->sl,
                'status' => $request->trang_thai,
            ] );
            return response()->json( [
                'status'    => true,
                'chi_tiet_san_pham' => $chi_tiet_san_pham,
            ] );
        } else {
            return response()->json( [
                'status'    => false,
            ] );
        }
    }

    public function changeStatus( $id ) {

        $chi_tiet_san_pham = ChiTietSanPhamModel::find( $id );
        if ( $chi_tiet_san_pham ) {
            if ( $chi_tiet_san_pham->status == 1 ) {
                $chi_tiet_san_pham->status = 0;
            } else {
                $chi_tiet_san_pham->status = 1;
            }
            $chi_tiet_san_pham->save();
            return response()->json( [ 'status' => true ] );
        }
    }

    public function delete( $id ) {
        $chi_tiet_san_pham = ChiTietSanPhamModel::find( $id );
        if ( $chi_tiet_san_pham ) {
            $chi_tiet_san_pham->delete();
            return response()->json( [ 'status' => true ] );
        } else {
            return response()->json( [
                'status'  =>  false,
            ] );
        }
    }

    public function edit( $id ) {
        $chi_tiet_san_pham = ChiTietSanPhamModel::find( $id );
        if ( $chi_tiet_san_pham ) {
            return response()->json( [
                'status'  =>  true,
                'chi_tiet_san_pham' => $chi_tiet_san_pham,
            ] );
        } else {
            toastr()->error( 'Chi tiết sản phẩm không tồn tại!' );
            return redirect()->back();
        }
    }

    public function update( editchitietsanphamrequest $request ) {
        $chi_tiet_san_pham = ChiTietSanPhamModel::find( $request->id );
        if ( !$chi_tiet_san_pham ) {
            return response()->json( [
                'status' => false,
                ] );
            } else {
                $exist = ChiTietSanPhamModel::select( '*' )
                ->where( 'id_sanpham', $request->id_sanpham )
                ->where( 'id_mau', $request->id_mau, )
                ->where( 'id_size', $request->id_size )
                ->first();

                // dd($chi_tiet_san_pham['id'] === $exist['id']);   
                if ( is_null( $exist ) ||$chi_tiet_san_pham['id'] === $exist['id'] ) {
                $chi_tiet_san_pham->update( [
                    'id_sanpham' => $request->id_sanpham,
                    'id_mau' => $request->id_mau,
                    'id_size' => $request->id_size,
                    'sl_chi_tiet' => $request->sl,
                    'status' => $request->trang_thai,
                ] );
                return response()->json( [
                    'status' => true,
                    'data'  => $chi_tiet_san_pham,
                ] );

            }

        }
    }

    public function search( Request $request ) {
        if ( $request->all() == null ) {
            $data = DB::table( 'chi_tiet_san_pham' )
            ->join( 'size', 'chi_tiet_san_pham.id_size', '=', 'size.id' )
            ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', '=', 'mau_sac.id' )
            ->join( 'san_phams', 'chi_tiet_san_pham.id_sanpham', '=', 'san_phams.id' )
            ->select( 'chi_tiet_san_pham.id', 'chi_tiet_san_pham.sl_chi_tiet', 'chi_tiet_san_pham.status', 'san_phams.ten_san_pham', 'mau_sac.ten_mau', 'size.size' )
            ->get();
        } else {
            $data = DB::table( 'chi_tiet_san_pham' )
            ->join( 'size', 'chi_tiet_san_pham.id_size', '=', 'size.id' )
            ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', '=', 'mau_sac.id' )
            ->join( 'san_phams', 'chi_tiet_san_pham.id_sanpham', '=', 'san_phams.id' )
            ->select( 'chi_tiet_san_pham.id', 'chi_tiet_san_pham.sl_chi_tiet', 'chi_tiet_san_pham.status', 'san_phams.ten_san_pham', 'mau_sac.ten_mau', 'size.size' )
            ->where( 'san_phams.ten_san_pham', 'like', '%' . $request->search . '%' )
            ->orwhere( 'mau_sac.ten_mau', 'like', '%' . $request->search . '%' )
            ->orwhere( 'size.size', 'like', '%' . $request->search . '%' )
            ->get();
        }
        return response()->json( [ 'data' => $data ] );
    }
}

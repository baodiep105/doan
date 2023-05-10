<?php

namespace App\Http\Controllers;

use App\Http\Requests\editKhuyenMaiRequest;
use App\Http\Requests\KhuyenMaiRequest;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KhuyenMaiController extends Controller {
    public function index() {
        return view( 'admin.ql_khuyen_mai' );
    }

    public function getData() {
        $sanPham = DB::table( 'san_phams' )
        ->join( 'hinh_anh', 'san_phams.id', 'hinh_anh.id' )
        ->select( 'san_phams.ten_san_pham', 'hinh_anh.hinh_anh' )
        ->get();
        $sanPham = SanPham::where( 'is_open', 1 )->get();
        $khuyen_mai = DB::table( 'khuyen_mai' )
        ->join( 'san_phams', 'khuyen_mai.id_san_pham', 'san_phams.id' )
        ->select( 'khuyen_mai.*', 'san_phams.ten_san_pham' )->orderBy( 'created_at', 'DESC' )->paginate( 8 );
        return response()->json( [
            'sanPham'  => $sanPham,
            'ds_khuyen_mai' => $khuyen_mai,
        ] );
    }

    public function create( KhuyenMaiRequest $request ) {
        $sanPham = SanPham::find( $request->id_san_pham );
        if ( $sanPham ) {
            $data = KhuyenMai::create( [
                'id_san_pham' => $request->id_san_pham,
                'ty_le' => $request->ty_le,
                'is_open' => $request->is_open,
            ] );
            return response()->json( [
                'trangThai' =>  true,
                'data' => $data,
            ] );
        }
        return response()->json( [
            'trangThai' =>  false,

        ] );
    }

    public function doiTrangThai( $id ) {
        $khuyen_mai = KhuyenMai::find( $id );
        if ( !$khuyen_mai ) {
            return response()->json( [
                'trangThai'         =>  false,
            ] );
        } else {
            $khuyen_mai->is_open = !$khuyen_mai->is_open;
            $khuyen_mai->save();
            return response()->json( [
                'trangThai'         =>  true,
                'tinhTrangKhuyenMai'  =>  $khuyen_mai->is_open,
            ] );
        }
    }

    public function delete( $id ) {
        $khuyen_mai = KhuyenMai::find( $id );
        if ( !$khuyen_mai ) {
            return response()->json( [
                'status'  =>  false,
            ] );
        } else {
            $khuyen_mai->delete();
            return response()->json( [
                'status'  =>  true,

            ] );
        }
    }

    public function edit( $id ) {
        $khuyen_mai = KhuyenMai::find( $id );
        if (! $khuyen_mai ) {
        toastr()->error( 'Ảnh không tồn tại!' );
        return redirect()->back();
    } else {
            return response()->json( [
                'status'  =>  true,
                'khuyen_mai'    =>  $khuyen_mai,
            ] );
        }
    }

    public function update( editKhuyenMaiRequest $request ) {
        // $data     = $request->all();
        $khuyen_mai = KhuyenMai::find( $request->idEdit );
        if (! $khuyen_mai ) {
            return response()->json( [
                'status'    => false,
                ] );
        } else {
                $khuyen_mai->update( [
                    'id_san_pham' => $request->id_san_pham_edit,
                    'ty_le' => $request->ty_le_edit,
                    'is_open' => $request->is_open_edit,
                ] );
                return response()->json( [
                    'status' => true,
                    'data'  => $khuyen_mai,
                ] );
        }
    }

    public function search( Request $request ) {

        if ( $request->all() == null ) {
            $data = DB::table( 'khuyen_mai' )
            ->join( 'san_phams', 'khuyen_mai.id_san_pham', 'san_phams.id' )
            ->select( 'khuyen_mai.*', 'san_phams.ten_san_pham' )
            ->get();
        } else {
            $data = DB::table( 'khuyen_mai' )
            ->join( 'san_phams', 'khuyen_mai.id_san_pham', 'san_phams.id' )
            ->where( 'san_phams.ten_san_pham', 'like', '%' . $request->search .'%' )
            ->orwhere( 'khuyen_mai.ty_le', 'like', '%' . $request->search .'%' )
            ->select( 'khuyen_mai.*', 'san_phams.ten_san_pham' )
            ->get();
        }
        return response()->json( [ 'dataProduct' => $data ] );
    }
}

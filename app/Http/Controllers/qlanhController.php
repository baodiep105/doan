<?php

namespace App\Http\Controllers;

use App\Http\Requests\editAnhRequest;
use App\Http\Requests\AnhRequest;
use App\Models\Anh;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class qlanhController extends Controller {
    public function index() {
        return view( 'admin.SanPham.ql_anh' );
    }

    public function create( AnhRequest $request ) {
        $anh = Anh::create( [
            'hinh_anh' => $request->hinh_anh,
            'id_san_pham' => $request->id_san_pham,
        ] );
        return response()->json( [
            'trangThai'         =>  true,
            'anh' => $anh,
        ] );
    }

    public function getData() {
        $sanPham = DB::table( 'hinh_anh' )->join( 'san_phams', 'hinh_anh.id_san_pham', 'san_phams.id' )->select( 'hinh_anh.*', 'san_phams.ten_san_pham' )->orderBy( 'created_at', 'DESC' )->paginate( 8 );
        $data = SanPham::where( 'is_open', 1 )->get();
        return response()->json( [
            'sanPham'  => $sanPham,
            'data'     =>  $data,
        ] );
    }

    public function update( editAnhRequest $request ) {
        // $data     = $request->all();
        $anh = Anh::find( $request->id );
        if ( !$anh ) {
            toastr()->error( 'Ảnh không tồn tại!' );
            return redirect()->back();
        } else {
            $anh->update( [
                'id_san_pham'      =>  $request->id_san_pham,
                'hinh_anh'         => $request->hinh_anh,
            ] );
            return response()->json( [
                'status' => true,
                'data'  => $request->all(),
            ] );
        }
    }

    public function edit( $id ) {
        $anh = Anh::find( $id );
        if ( !$anh ) {
            toastr()->error( 'Ảnh không tồn tại!' );
            return redirect()->back();
        } else {
            return response()->json( [
                'status'  =>  true,
                'anh'    =>  $anh,
            ] );
        }
    }

    public function delete( $id ) {
        $anh = Anh::find( $id );
        if ( !$anh ) {
            return response()->json( [
                'status'  =>  false,
            ] );
        } else {
            $anh->delete();
            return response()->json( [
                'status'  =>  true,
            ] );
        }
    }

    public function search( Request $request ) {
        if ( $request->search == '' ) {
            $data = DB::table( 'hinh_anh' )->join( 'san_phams', 'hinh_anh.id_san_pham', 'san_phams.id' )->select( 'san_phams.ten_san_pham', 'hinh_anh.hinh_anh' )->orderBy( 'hinh_anh.created_at', 'DESC' )->get();
        } else {
            $data = DB::table( 'hinh_anh' )->join( 'san_phams', 'hinh_anh.id_san_pham', 'san_phams.id' )->where( 'san_phams.ten_san_pham', 'like', '%' . $request->search . '%' )->select( 'san_phams.ten_san_pham', 'hinh_anh.hinh_anh' )->orderBy( 'hinh_anh.created_at', 'DESC' )->get();
        }
        return response()->json( [
            'data' => $data
        ] );
    }
}

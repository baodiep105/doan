<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class DonHangController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        return view( 'admin.ql_don_hang' );
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getData() {
        $donhang = DB::table( 'don_hangs' )
        ->leftjoin( 'users', 'don_hangs.email', 'users.email' )
        ->select( 'don_hangs.*', 'users.username' )
        ->orderBy('created_at','DESC')
        ->paginate( 8 );

        return response()->json( [
            'donhang' => $donhang,
        ] );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function changeStatus( $id, Request $request ) {
        $donhang = DonHang::find( $id );
        // dd( $id );
        if ( !$donhang ) {
            return response()->json( [
                'status' => false
            ] );
        }
        $donhang->status = $request->value;
        $donhang->save();
        return response()->json( [ 'status' => true ] );
    }

    public function delete( $id ) {
        $donHang = DonHang::find( $id );

        if ( !$donHang ) {
            return response()->json( [
                'status'  =>  false,
            ] );
        } else {
            DB::table( 'don_hangs as dh' )->join( 'chi_tiet_don_hang as ctdh', 'dh.id', 'ctdh.don_hang_id' )->where( 'dh.id', $id )->delete();
            return response()->json( [ 'status' => true ] );
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\DonHang  $donHang
    * @return \Illuminate\Http\Response
    */

    public function detail( $id ) {
        $san_pham = array();
        $total = 0;
        // $b = DB::table( 'chi_tiet_don_hangs' )->join( 'don_hangs', 'chi_tiet_don_hangs.don_hang_id', 'don_hangs.id' )->where( 'don_hang_id', $id )->get();
        $chitietdonhang = DB::table( 'chi_tiet_don_hangs' )->join( 'don_hangs', 'chi_tiet_don_hangs.don_hang_id', 'don_hangs.id' )
        ->join( 'chi_tiet_san_pham','chi_tiet_don_hangs.id_chi_tiet_san_pham','chi_tiet_san_pham.id')
        // ->join( 'chi_tiet_don_hangs', 'chi_tiet_san_pham.id', 'chi_tiet_don_hangs.id_chi_tiet_san_pham' )
        ->join( 'san_phams', 'chi_tiet_san_pham.id_sanpham', 'san_phams.id' )
        ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', 'mau_sac.id' )
        ->join( 'size', 'chi_tiet_san_pham.id_size', 'size.id' )
        // ->join( 'don_hangs', 'chi_tiet_don_hangs.don_hang_id', 'don_hangs.id' )
        ->where( 'don_hang_id', $id )
        ->select( 'chi_tiet_don_hangs.*', 'chi_tiet_san_pham.*', 'san_phams.ten_san_pham' )
        ->get();
        foreach ( $chitietdonhang as $ey => $value ) {
            $sanpham = new stdClass;
            $sanpham = $value;
            $sanpham->total = $value->don_gia*$value->so_luong;
            $total += $value->so_luong * $value->don_gia;
            // dd( $sanpham );
            array_push( $san_pham, $sanpham );
        }
        $hinh_anh = DB::table( 'hinh_anh' )->get();
        $id = array();
        foreach ( $chitietdonhang as $value ) {
            array_push( $id, $value->id_sanpham );
        }
        $anh = array();
        foreach ( $id as $key ) {
            foreach ( $hinh_anh as $value ) {
                if ( $key == $value->id_san_pham ) {
                    array_push( $anh, $value );
                    break;
                }
            }
        }
        foreach ( $san_pham as $value ) {
            $total += $value->total;
            foreach ( $anh as $key ) {
                if ( $value->id_sanpham == $key->id_san_pham ) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }
        return view( 'admin.chi_tiet_hoa_don', compact( 'san_pham', 'total' ) );
    }

    public function search( Request $request ) {
        if ( $request->all() == null ) {
            $data = DB::table( 'don_hangs' )
            ->join( 'users', 'don_hangs.email', 'users.email' )
            ->select( 'don_hangs.*', 'users.username' )
            ->orderBy('created_at','DESC')
            ->get();
        } else {
            $data = DB::table( 'don_hangs' )
            ->select( 'don_hangs.*' )
            ->Where( 'email', 'like', '%' . $request->search . '%' )
            ->orWhere( 'sdt', 'like', '%' . $request->search . '%' )
            // ->orWhere( 'created_at', 'like', '%' . $request->search . '%' )
            ->orderBy('created_at','DESC')
            ->get();
        }
        return response()->json( [ 'data' => $data ] );
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\DonHang  $donHang
    * @return \Illuminate\Http\Response
    */

    public function destroy( DonHang $donHang ) {
        //
    }
}

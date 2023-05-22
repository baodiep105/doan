<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\DanhMucAPIController;
use App\Http\Requests\DanhMucSanPham as RequestsDanhMucSanPham;
use App\Http\Resources\DanhMucSanPhamResource;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Response as HttpResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\editDanhMucRequest;
use App\Http\Requests\updatdeDanhMucRequest;
use App\Models\ChiTietSanPhamModel;
use App\Models\DanhMucSanPham;
use App\Models\SanPham;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Page;
// use toan_cuc;

class DanhMucSanPhamController extends Controller {
    protected $danhMucSanPham;
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        return view( 'admin.danh_muc' );
    }

    public function create( RequestsDanhMucSanPham $request ) {
        $danhmuc = DanhMucSanPham::create( [
            'ten_danh_muc'      =>  $request->ten_danh_muc,
            'hinh_anh'          =>  $request->hinh_anh,
            'id_danh_muc_cha'   =>  empty( $request->id_danh_muc_cha ) ? 0 : $request->id_danh_muc_cha,
            'is_open'           =>  $request->is_open,
        ] );

        return response()->json( [
            'trangThai'         =>  true,
            'danhMuc'           => $danhmuc,
        ] );
    }

    public function getData() {
        $danh_muc_cha = DanhMucSanPham::where( 'id_danh_muc_cha', 0 )->get();

        $sql = 'SELECT a.*, b.ten_danh_muc as `ten_danh_muc_cha`
                FROM `danh_muc_san_phams` a LEFT JOIN `danh_muc_san_phams` b
                on a.id_danh_muc_cha = b.id';

        $data = DB::table( 'danh_muc_san_phams as dm1' )->leftJoin( 'danh_muc_san_phams as dm2', 'dm1.id_danh_muc_cha', 'dm2.id' )->select( 'dm1.*', 'dm2.ten_danh_muc as ten_danh_muc_cha' )->orderBy('created_at','DESC')->paginate( 8 );
        return response()->json( [
            'danh_muc'          => $data,
            'danh_muc_cha'  => $danh_muc_cha,
        ] );
    }

    public function doiTrangThai( $id ) {
        $danh_muc = DanhMucSanPham::find( $id );
        if ( !$danh_muc ) {
            return response()->json( [
                'trangThai'         =>  false,
            ] );
        } else {
            $danh_muc->is_open = !$danh_muc->is_open;
            $danh_muc->save();
            return response()->json( [
                'trangThai'         =>  true,
                'tinhTrangDanhMuc'  =>  $danh_muc->is_open,
            ] );
        }
    }

    public function update( updatdeDanhMucRequest $request ) {
        $danh_muc = DanhMucSanPham::find( $request->idEdit );
        if ( !$danh_muc ) {
            return response()->json( [
                'status' => false,
            ] );
        }
        $danh_muc->update( [
            'ten_danh_muc'      =>  $request->ten_danh_muc_edit,
            'slug_danh_muc'     =>  Str::slug( $request->ten_danh_muc_edit ),
            'hinh_anh'          =>  $request->hinh_anh_edit,
            'id_danh_muc_cha'   =>  empty( $request->id_danh_muc_cha_edit ) ? 0 : $request->id_danh_muc_cha_edit,
            'is_open'           =>  $request->is_open_edit,
        ] );
        return response()->json( [
            'status' => true,
            'data'  => $request->all(),
        ] );
    }

    public function edit( $id ) {
        $danh_muc = DanhMucSanPham::find( $id );
        if ( !$danh_muc ) {
            return response()->json( [
                'status'  =>  false, ]
            );
        } else {
            $danh_muc_cha = DanhMucSanPham::where( 'id_danh_muc_cha', 0 )->get();
            return response()->json( [
                'status'  =>  true,
                'danhMuc'    =>  $danh_muc,
                'danhMucCha' => $danh_muc_cha,
            ] );
            // toastr()->error( 'Danh mục không tồn tại!' )
        }
    }

    public function delete( $id ) {
        $danh_muc = DanhMucSanPham::find( $id );
        if ( !$danh_muc ) {
            toastr()->error( 'Danh mục tồn tại!' );
            return redirect()->back();
        } else {
            DB::table( 'danh_muc_san_phams as dm' )->leftJoin( 'san_phams as sp', 'dm.id', 'sp.id_danh_muc' )
            ->leftJoin( 'chi_tiet_san_pham', 'sp.id', 'chi_tiet_san_pham.id_sanpham' )
            ->leftJoin( 'hinh_anh', 'sp.id', 'hinh_anh.id_san_pham' )
            ->leftJoin( 'khuyen_mai', 'sp.id', 'khuyen_mai.id_san_pham' )->where( 'dm.id', $id )->delete();
            // $danh_muc->delete();
            return response()->json( [
                'status'  =>  true,
            ] );
        }
    }

    public function search( Request $request ) {
        if ( is_null( $request->search ) ) {
            $data = DB::table( 'danh_muc_san_phams as a' )->leftJoin( 'danh_muc_san_phams as b', 'a.id_danh_muc_cha', 'b.id' )->select( 'a.*', 'b.ten_danh_muc as ten_danh_muc_cha' )->orderBy('created_at','DESC')->get();
        } else {
            $data = DB::table( 'danh_muc_san_phams as a' )
            ->leftJoin( 'danh_muc_san_phams as b', 'a.id_danh_muc_cha', 'b.id' )
            ->select( 'a.*', 'b.ten_danh_muc as ten_danh_muc_cha' )
            ->Where( 'a.ten_danh_muc', 'like', '%' . $request->search . '%' )
            // ->orWhere( 'a.created_at', 'like', '%' . $request->search . '%' )
            ->orderBy('created_at','DESC')
            ->get();
        }
        return response()->json( [
            'search' => $data
        ] );
    }
}

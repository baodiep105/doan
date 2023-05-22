<?php

namespace App\Http\Controllers;

use App\Http\Requests\request as RequestsRequest;
use App\Models\ChiTietSanPhamModel;
use App\Models\DanhGia;
use App\Models\DonHang;
use App\Models\SanPham;
use ChiTietSanPham;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

class detailController extends Controller {
    public function detail( $id ) {
        //chi tiet san pham
        $detail = DB::table( 'san_phams' )->leftjoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
        ->join( 'danh_muc_san_phams', 'san_phams.id_danh_muc', 'danh_muc_san_phams.id' )
        ->where( 'san_phams.id', $id )
        ->select( 'san_phams.*', 'danh_muc_san_phams.ten_danh_muc', 'khuyen_mai.ty_le as khuyen_mai' )->first();
        //list mau
        $mau = DB::table( 'chi_tiet_san_pham' )
        ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', 'mau_sac.id' )
        ->where( 'chi_tiet_san_pham.id_sanpham', $id )
        ->where( 'chi_tiet_san_pham.status', 1 )
        // ->selectRaw( 'sum(chi_tiet_san_pham.so_luong) as so_luong' )
        ->select( 'mau_sac.id', 'mau_sac.ten_mau', 'mau_sac.hex' )
        ->groupBy( 'mau_sac.id', 'mau_sac.ten_mau', 'mau_sac.hex' )
        ->get();
        //List size
        $size = DB::table( 'chi_tiet_san_pham' )
        ->join( 'size', 'chi_tiet_san_pham.id_size', 'size.id' )
        ->where( 'chi_tiet_san_pham.id_sanpham', $id )
        ->where( 'chi_tiet_san_pham.status', 1 )
        ->select( 'size.id', 'size.size' )
        ->groupBy( 'size.id', 'size.size' )
        ->get();
        //dd( $size );
        //thêm thuộc tính ảnh cho sản phẩm liên quan
        $id_danh_muc = $detail->id_danh_muc;
        $lienquan = DB::table( 'san_phams' )->leftjoin( 'khuyen_mai', 'san_phams.id', 'khuyen_mai.id_san_pham' )
        ->where( 'san_phams.is_open', 1 )
        ->where( 'id_danh_muc', $id_danh_muc )
        ->where( 'san_phams.id', '<>', $id )
        ->select( 'san_phams.*', 'khuyen_mai.ty_le as khuyen_mai' )->take( 5 )->get();
        $hinh_anh = DB::table( 'hinh_anh' )->get();
        $id_san_pham = array();
        foreach ( $lienquan as $value ) {
            array_push( $id_san_pham, $value->id );
        }
        $anh = array();
        foreach ( $id_san_pham as $key )
        foreach ( $hinh_anh as $value ) {
            if ( $key == $value->id_san_pham ) {
                array_push( $anh, $value );
                break;
            }
        }

        foreach ( $lienquan as $value ) {
            foreach ( $anh as $key ) {
                if ( $value->id == $key->id_san_pham ) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }
        //tất cả ảnh của sản phẩm
        $anh = DB::table( 'hinh_anh' )->where( 'id_san_pham', $id )->get();
        //show số lượng chi tiết
        $tong = ChiTietSanPhamModel::where( 'id_sanpham', $id )->where( 'status', 1 )->sum( 'sl_chi_tiet' );
        $so_luong_object = new stdClass;
        //---------------------------------------------------object tổng-----------------------------------------------------------------
        $so_luong_object->tong = $tong;
        $chi_tiet_mau_array = array();
        //khởi tạo mảng chi tiết màu
        foreach ( $mau as $value ) {
            $chi_tiet_mau_object = new stdClass;
            //khởi tạo object chi tiết màu
            $chi_tiet_size_Array = array();
            //khởi tạo mảng chi tiết size

            $chi_tiet_mau_object->id_mau = $value->id;
            //them thuộc tính id_mau
            $chi_tiet_mau_object->ten_mau = $value->ten_mau;
            //thêm thuộc tính tên màu
            $chi_tiet_mau_object->hex = $value->hex;
            //thêm thuộc tính hex
            $chi_tiet_mau_object->so_luong = DB::table( 'chi_tiet_san_pham' )                       //thêm thuộc tính số lượng
            ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', 'mau_sac.id' )
            ->where( 'chi_tiet_san_pham.id_sanpham', $id )
            ->where( 'mau_sac.id', $value->id )
            ->where( 'status', 1 )
            ->select( 'mau_sac.id', 'mau_sac.ten_mau', 'mau_sac.hex' )
            ->sum( 'sl_chi_tiet' );
            foreach ( $size as $items ) {
                $is_exists = DB::table( 'chi_tiet_san_pham' )
                ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', 'mau_sac.id' )
                ->join( 'size', 'chi_tiet_san_pham.id_size', 'size.id' )
                ->where( 'chi_tiet_san_pham.id_sanpham', $id )
                ->where( 'mau_sac.id', $value->id )
                ->where( 'size.id', $items->id )
                ->where( 'status', 1 )
                ->exists();
                if ( $is_exists ) {
                    //Kiểm tra có tồn tại không nếu có khởi tạo object chi tiết size va thêm vào mảng chi_tiet_size array
                    $chi_tiet_size_object = new stdClass;
                    //khởi tạo object chi tiết size
                    $chi_tiet_size = DB::table( 'chi_tiet_san_pham' )
                    ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', 'mau_sac.id' )
                    ->join( 'size', 'chi_tiet_san_pham.id_size', 'size.id' )
                    ->where( 'chi_tiet_san_pham.id_sanpham', $id )
                    ->where( 'mau_sac.id', $value->id )
                    ->where( 'size.id', $items->id )
                    ->where( 'status', 1 )
                    ->select( 'chi_tiet_san_pham.*', 'size.size', )
                    ->first();
                    $chi_tiet_size_object->id_chi_tiet_san_pham = $chi_tiet_size->id;
                    //thêm thuộc tính id_chi_tiet_san_pham cho object chi tiết size
                    $chi_tiet_size_object->id = $chi_tiet_size->id_size;
                    //thêm thuộc tính id_size cho object chi tiết size
                    $chi_tiet_size_object->size = $chi_tiet_size->size;
                    //thêm thuộc tính ten_size cho object chi tiết size
                    $chi_tiet_size_object->so_luong = $chi_tiet_size->sl_chi_tiet;
                    array_push( $chi_tiet_size_Array, $chi_tiet_size_object );
                    //add object object chi tiết size vảo mảng chi_tiet_size_Array
                }
            }
            $chi_tiet_mau_object->size = $chi_tiet_size_Array;
            //add thuộc tính chi tiết size = chi_tiet_size_Array
            array_push( $chi_tiet_mau_array, $chi_tiet_mau_object );
            //add object chi_tiet_mau_object vào mảng chi_tiet_mau_array
            $so_luong_object->mau = $chi_tiet_mau_array;
            // add mảng chi_tiet_mau_array vào object tổng
        }
        return response()->json( [
            'data'  => $detail,
            'mau' => $mau,
            'size' => $size,
            'san_pham_lien_quan' => $lienquan,
            // 'so_luong' => $tong_so_luong,
            'hinh_anh' => $anh,
            'so_luong' => $so_luong_object,
        ] );
    }

    public function danhGia( $id, Request $request ) {

        $rules = [
            'sao' => 'required|numeric|min:1',
            'email' => 'required|email',
            'content'  => 'required',
        ];
        // dd( $request->email );
        $input     = $request->all();
        $validator = Validator::make( $input, $rules,[
            'required'      =>  'vui lòng nhập:attribute ',
            'max'           =>  ':attribute phải đúng 10 chữ số',
            'min'           =>  'vui lòng nhập số :attribute ',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
        ],[
            'sao'      =>  'email',
            'email'     =>  'người nhận',
            'content'   =>  'số điện thoại',

        ] );
        if ( $validator->fails() ) {
            $error = array();
            $danh_sach_loi = $validator->errors()->messages();
            // dd( $danh_sach_loi );
            foreach ( $danh_sach_loi as  $key=>$value ) {
                // echo $key;
                array_push( $error, $value );
                // toastr()->error( $value[ 0 ] );
            }
            return response()->json( [
                'status'=>'error',
                'errors'=>$error,
            ] );
        }
        $exist = DB::table( 'chi_tiet_don_hangs as ct' )
        ->join( 'don_hangs as dh', 'ct.don_hang_id', 'dh.id' )
        ->join( 'chi_tiet_san_pham as ctsp', 'ct.id_chi_tiet_san_pham', 'ctsp.id' )
        ->where( 'dh.email', $request->email )
        ->where( 'ctsp.id_sanpham', $id )
        ->exists();

        // return response()->json( [
        //     'status' => 'success',
        //     'data'  => $exist,
        // ] );
        if ( $exist ) {
            $danh_gia = DanhGia::create( [
                'content'   => $request->content,
                'rate'       => $request->sao,
                'email' => $request->email,
                'id_san_pham' => $id,
            ] );
            return response()->json( [
                'status' => 'success',
                'data'  => $danh_gia,
            ] );
        } else {
            return response()->json( [
                'status' => 'error',
                'message' => 'bạn cần phải mua sản phẩm để đánh giá'
            ] );
        }
    }

    public function listDanhGia( $id ) {
        // $replied=DB::table('danh_gias as sub')->where('sub.id_san_pham',$id)
        $data = DB::table( 'danh_gias' )->where( 'id_san_pham', $id )->orderBy( 'created_at', 'DESC' )->select( 'danh_gias.*' )->get();
        return response()->json( [
            'status'    => 'success',
            'data'  => $data,
        ] );
    }

    // public function addCart( Request $request ) {
    //     $chi_tiet_san_pham = ChiTietSanPhamModel::where( 'id', $request->id_chi_tiet_san_pham )->first();
    //     if ( $chi_tiet_san_pham ) {
    //         if ( $chi_tiet_san_pham->sl_chi_tiet - $request->so_luong >= 0 ) {
    //             $chi_tiet_san_pham->sl_chi_tiet = $chi_tiet_san_pham->sl_chi_tiet - $request->so_luong;
    //             $chi_tiet_san_pham->save();
    //             return response()->json( [
    //                 'status'  => 'success',
    //                 'data'  => $chi_tiet_san_pham->sl_chi_tiet,
    //             ] );
    //         }
    //         return response()->json( [
    //             'status' => 'erros',
    //             'message' => 'số lượng không đủ',
    //         ] );
    //     }
    //     return response()->json( [
    //         'status'    => 'error',
    //         'message'   => 'sản phẩm không tồn tại',
    //     ] );
    // }
}

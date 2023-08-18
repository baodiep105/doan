<?php

namespace App\Http\Controllers;

use App\Http\Requests\donhangRequest;
use App\Models\ChiTietDonHang;
use App\Models\ChiTietSanPhamModel;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PharIo\Manifest\Email;
use Carbon\Carbon;
use stdClass;
use Config;
// use Response;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Response;
// use App\Http\Controllers\Illuminate\Http\Response;
// use Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;

class add_cartController extends Controller {
    public function ReturnURL( Request $request ) {
        // dd(!$request->all());
        if(!$request->all()){
            return response()->json([
                'status'=>'error'
            ]);
        }
        $don_hang = DonHang::create( [
            'email' => $request->data[ 'email' ],
            'tong_tien' => $request->data[ 'tong_tien' ],
            'tien_giam_gia' => $request->data[ 'tien_giam' ],
            'thuc_tra' => $request->data[ 'thuc_tra' ],
            'status' => 1,
            'dia_chi' => $request->data[ 'dia_chi' ],
            'nguoi_nhan' => $request->data[ 'nguoi_nhan' ],
            'sdt' => $request->data[ 'sdt' ],
            'ghi_chu' => $request->data[ 'ghi_chu' ],
            'loai_thanh_toan'=>$request->type,
        ] );
        foreach ( $request->data[ 'don_hang' ]  as $value ) {
            $chi_tiet_san_pham = ChiTietSanPhamModel::where( 'id', $value[ 'id_chi_tiet_san_pham' ] )->first();
            if($value['so_luong']>$chi_tiet_san_pham->sl_chi_tiet){
                return response()->json([
                    'status'=>'error',
                    'massage'=>'Số lượng sản phẩm trong kho không đủ'
                ]);
            }
            $chiTietDonHang = ChiTietDonHang::create( [
                'id_chi_tiet_san_pham' => $value[ 'id_chi_tiet_san_pham' ],
                'don_gia' => $value[ 'don_gia' ],
                'so_luong' => $value[ 'so_luong' ],
                'don_hang_id' => $don_hang->id,
            ] );

            $chi_tiet_san_pham->sl_chi_tiet -= $value[ 'so_luong' ];
            $chi_tiet_san_pham->save();
        }
        return response()->json( [
            'status' => 'success',
            'email' => $don_hang,
        ] );
    }



    public function vnpay( $amount ) {
        $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $vnp_Returnurl = config( 'global.link_user' ).'/direction?fbclid=IwAR1wJzmlbTCmITiQ5nNIHINeIMu6cEylupOwP3Tfi6aXtDj65i1iRL2miis';
        $vnp_TmnCode = 'TKIKN7N0';
        //Mã website tại VNPAY
        $vnp_HashSecret = 'JRCQGHNEQULNVFQJWJQSICRRIFAEBSKK';
        //Chuỗi bí mật
        $vnp_TxnRef = Carbon::now()->toDateTimeString();
        //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount[ 'thuc_tra' ]*100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER[ 'REMOTE_ADDR' ];
        $inputData = array(
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => date( 'YmdHis' ),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_Locale' => $vnp_Locale,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => $vnp_OrderType,
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_TxnRef' => $vnp_TxnRef,
        );

        if ( isset( $vnp_BankCode ) && $vnp_BankCode != '' ) {
            $inputData[ 'vnp_BankCode' ] = $vnp_BankCode;
        }
        if ( isset( $vnp_Bill_State ) && $vnp_Bill_State != '' ) {
            $inputData[ 'vnp_Bill_State' ] = $vnp_Bill_State;
        }
        ksort( $inputData );
        $query = '';
        $i = 0;
        $hashdata = '';
        foreach ( $inputData as $key => $value ) {
            if ( $i == 1 ) {
                $hashdata .= '&' . urlencode( $key ) . '=' . urlencode( $value );
            } else {
                $hashdata .= urlencode( $key ) . '=' . urlencode( $value );
                $i = 1;
            }
            $query .= urlencode( $key ) . '=' . urlencode( $value ) . '&';
        }
        $vnp_Url = $vnp_Url . '?' . $query;
        if ( isset( $vnp_HashSecret ) ) {
            $vnpSecureHash =   hash_hmac( 'sha512', $hashdata, $vnp_HashSecret );
            //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return response()->json( [
            'status'=>'success',
            'link'=>$vnp_Url,
            'loai_thanh_toan'=>1,
            'data'=>$amount

        ] );
    }


    public function DonHang( Request $request ) {

        $validator =  Validator::make( $request->all(), [
            'email'        =>  'required|email',
            'nguoi_nhan'      =>   'required',
            'sdt'           =>  'required|min:10|max:10',
            'dia_chi'                =>  'required',
        ], [
            'required'      =>  ':attribute không được để trống',
            'max'           =>  ':attribute phải đúng 10 chữ số',
            'min'           =>  ':attribute phải đúng 10 chữ số',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
        ], [
            'email'      =>  'email',
            'nguoi_nhan'     =>  'người nhận',
            'sdt'   =>  'số điện thoại',
            'dia_chi'   =>  'địa chỉ',
        ] );
        if ( $validator->fails() ) {
            $error = array();
            $danh_sach_loi = $validator->errors()->messages();

            foreach ( $danh_sach_loi as  $key=>$value ) {

                array_push( $error, $value );

            }
            return response()->json( [
                'status'=>'error',
                'error'=>$error,
            ] );
        }
        if ( count( $request->don_hang ) > 0 ) {
            foreach ( $request->don_hang as $value ) {
                $san_pham = ChiTietSanPhamModel::find( $value[ 'id_chi_tiet_san_pham' ] );
                if ( $value[ 'so_luong' ] > $san_pham->sl_chi_tiet ) {
                    return response()->json( [
                        'status' => 'error',
                        'message' => 'Số lượng trong kho không đủ',
                    ] );
                }
            }
            return $this->vnpay( $request->all());
        } else {
            return response()->json( [
                'status' => 'error',
                'message'=> 'hãy chọn sản phẩm cần mua',
            ] );
        }

    }

    public function LichSuDonHang( Request $request ) {
        if ( $request->email != null ) {
            $donhang = DB::table( 'don_hangs' )
            ->where( 'email', $request->email )
            ->orderBy( 'created_at', 'DESC' )
            ->get();
            return response()->json( [
                'status' => 'success',
                'donhang' => $donhang,
            ] );
        }
        return response()->json( [
            'status' => 'erorr',
        ] );
    }

    public function detail( $id ) {
        $san_pham = array();
        $chitietdonhang = DB::table( 'chi_tiet_san_pham' )
        ->join( 'chi_tiet_don_hangs', 'chi_tiet_san_pham.id', 'chi_tiet_don_hangs.id_chi_tiet_san_pham' )
        ->join( 'san_phams', 'chi_tiet_san_pham.id_sanpham', 'san_phams.id' )
        ->join( 'mau_sac', 'chi_tiet_san_pham.id_mau', 'mau_sac.id' )
        ->join( 'size', 'chi_tiet_san_pham.id_size', 'size.id' )
        ->join( 'don_hangs', 'chi_tiet_don_hangs.don_hang_id', 'don_hangs.id' )
        ->where( 'don_hang_id', $id )
        ->select( 'chi_tiet_don_hangs.*', 'mau_sac.hex', 'chi_tiet_san_pham.*', 'san_phams.ten_san_pham', 'mau_sac.ten_mau', 'size.size', )
        ->get();
        $total = 0;
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
            foreach ( $anh as $key ) {
                if ( $value->id_sanpham == $key->id_san_pham ) {
                    $value->hinh_anh = $key->hinh_anh;
                    break;
                }
            }
        }
        return response()->json( [
            'status' => 'success',
            'data' => $san_pham,
            'total'=> $total,
        ] );
    }
}

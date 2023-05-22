<?php

namespace App\Http\Controllers;

use App\Http\Requests\profileRequest;
use App\Mail\ForgetMail;
use App\Http\Requests\donhangRequest;
use App\Mail\SendMail;
use App\Models\ChiTietDonHang;
use App\Models\ChiTietSanPhamModel;
use App\Models\DanhGia;
use App\Models\DonHang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\add_cartController;
use Socialite;
use Carbon\Carbon;
use Config;
use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class UserController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function redirect() {
        $client = new GoogleClient();
        $client->setClientId( config( 'services.google.client_id' ) );
        $client->setClientSecret( config( 'services.google.client_secret' ) );
        $client->setRedirectUri( config( 'services.google.redirect' ) );
        $client->addScope( 'email' );
        $client->addScope( 'profile' );
        $authUrl = $client->createAuthUrl();
        // return redirect( $authUrl );
        // dd( $authUrl )
        return response()->json( [
            'status'=>'success',
            'url'=> $authUrl,
        ] );
    }

    public function google_login( Request $request ) {
        // dd( $_GET[ 'email' ] );
        $user = User::where( 'email', $request->email )->where( 'id_loai', 2 )->first();
        if ( empty( $user ) || is_null( $user ) ) {
            $user = User::create( [ 'username' => $request->name, 'email' => $request->email, 'is_email'=>1, 'id_loai' => 2 ] );
        }
        $token = $user->createToken( 'auth_token' )->plainTextToken;
        return response()->json( [
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ] );

    }

    public function callback( Request $request ) {
        $client = new GoogleClient();
        $client->setClientId( config( 'services.google.client_id' ) );
        $client->setClientSecret( config( 'services.google.client_secret' ) );
        $client->setRedirectUri( config( 'services.google.redirect' ) );
        $client->addScope( 'email' );
        $client->addScope( 'profile' );
        if ( $request->get( 'code' ) ) {
            $token = $client->fetchAccessTokenWithAuthCode( $request->get( 'code' ) );
            $oauth = new Oauth2( $client );
            $userData = $oauth->userinfo->get();

            $social_user = [
                'name' => $userData->name,
                'email' => $userData->email,
                'avatar' => $userData->picture,
                'token' => $token,
            ];
            return redirect( config( 'global.link_user' ).'/direction-login?email='.$social_user[ 'email' ].'&name='.$social_user[ 'name' ] );
        }
    }

    public function register( Request $request ) {
        $rules = [
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email|email:rfc,dns',
            'password' => 'required|min:6',
            're_password' => 'required|same:password',
        ];

        $input = $request->all();

        $validator = Validator::make( $input, $rules, [
            'required' => ':attribute không được để trống',
            'min' => ':attribute lớn hơn 5 ký tự',
            'unique' => ':attribute đã tồn tại',
            'numeric' => ':attribute phải là số',
            'same' => ':attribute phải giống password',
            'email'=>':attribute không đúng định dạng'
        ], [
            'username' => 'username',
            'email' => 'email',
            'password' => 'password',
            're_password' => 're_password',
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
        $username = $request->username;
        $email = $request->email;
        $password = $request->password;
        $hash = Str::uuid();
        $user = User::create( [ 'username' => $username, 'email' => $email, 'password' => bcrypt( $password ), 'id_loai' => 2, 'hash' => $hash, 'is_email' => 0 ] );

        Mail::to( $request->email )->send( new SendMail(
            $request->username,
            $hash,
            'Kích Hoạt Tài Khoảng Người Dùng',
        ) );
        if ( $user ) {
            return response()->json( [
                'status' => 'success',
            ] );
        }
    }

    public function active( $hash ) {
        $user = User::where( 'hash', $hash )->first();
        if ( $user->is_email ) {
            return '<h1>Tài khoản của bạn đã được kích hoạt trước đó</h1>';
        } else {
            $user->is_email = 1;
            $user->save();
            return "<h1>Tài khoản của bạn đã được kích hoạt!</h1> <a href='https://1978-2402-800-6294-1c26-b8ff-ff4e-85b0-c1a7.ngrok-free.app/login'><h3>Đăng nhập tại đây</h3></a>";
        }
    }

    public function login( Request $request ) {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $input = $request->all();

        $validator = Validator::make( $input, $rules, [
            'required' => ':attribute không được để trống',
        ], [
            'username' => 'username',
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
        if ( !Auth( 'web' )->attempt( $request->only( 'username', 'password' ) ) ) {
            return response()->json( [
                'status' => 'erorr',
                'message' => 'Username hoặc mật khẩu không đúng',
            ], 401 );
        }

        $user = User::where( 'username', $request[ 'username' ] )->firstOrFail();
        if ( $user->id_loai != 2 ) {
            return response()->json( [ 'status'=>'error', 'message'=>'Username hoặc mật khẩu không đúng' ] );
        }
        if ( $user->is_email == 1 && $user->id_loai == 2 ) {
            $token = $user->createToken( 'auth_token' )->plainTextToken;
            return response()->json( [
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
            ] );
        } else {
            return response()->json( [
                'status' => 'error',
                'message' => 'bạn cần phải kích hoạt mail để login  ',
            ] );
        }
    }

    public function logout() {
        if ( Auth::check() ) {
            Auth::user()->tokens()->delete();
            return response()->json( [

                'status' => 'success',
                'message' => 'User Logout',
            ], 200 );
        } else {
            return response()->json( [
                'status' => 'erorr',
                'message' => 'logout fail',
            ] );
        }
    }

    public function getme( Request $request ) {
        return response()->json( [
            'user' => Auth::guard( 'users' )->user(),
        ] );
    }

    public function danhgiaUser( $id, Request $request ) {

        $validator =  Validator::make( $request->all(), [
            'content' => 'required',
            'sao' => 'required',
        ], [
            'required'      =>  ':attribute k được để trố',
            'max'           =>  ':attribute phải đúng 10 số',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
            'min'           =>  ':attribute phải đúng 10 số'
        ], [
            'content'=>'đánh giá',
            'sao'=>'sao'
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
        ->join( 'chi_tiet_san_pham as ctsp', 'ct.id_chi_tiet_san_pham', 'ctsp.id' )
        ->join( 'don_hangs as dh', 'ct.don_hang_id', 'dh.id' )
        ->where( 'dh.email', auth()->user()->email )
        ->where( 'ctsp.id_sanpham', $id )
        ->get();
        if ( !$exist ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'bạn cần phải mua hàng để đánh giá',
            ] );

        } else {
            $danh_gia = DanhGia::create( [
                'content' => $request->content,
                'rate' => $request->sao,
                'email' => auth()->user()->email,
                'id_san_pham' => $id,
            ] );
            return response()->json( [
                'status' => 'success',
                'data' => $danh_gia,
            ] );
        }
    }

    public function forget_password( Request $request ) {
        $rules = [
            'email' => 'required|exists:users,email',
        ];
        $input = $request->all();
        $validator = Validator::make( $input, $rules, [
            'required' => ':attribute không được để trống',
            'exists' => ':attribute  chưa được đăng ký',
            'email' => ':attribute phải đúng định dạng ',
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
        $email = $request->email;
        $user = User::where( 'email', $email )->first();

        if ( $user ) {
            $hash = Str::random( 6 );
            $user->reset_password = $hash;
            $user->save();
            $username = $user->username;
            Mail::to( $request->email )->send( new ForgetMail(
                $username,
                $hash,
                'Đổi mật Khẩu Tài Khoảng Người Dùng',
            ) );
        }

        if ( $user ) {
            return response()->json( [
                'status' => 'success',
                'data' => $request->email,
            ] );
        }
    }

    public function xac_nhan( Request $request ) {
        $user = User::where( 'reset_password', $request->otp )->first();
        if ( !is_null( $user ) || !empty( $user ) ) {
            $user->reset_password=NULL;
            $user->save();
            return response()->json( [
                'status' => 'success',
                'email' => $user->email,
            ] );
        }
        return response()->json( [
            'status' => 'error',
            'message' => 'mã otp sai',
        ] );
    }

    public function reset_password( Request $request ) {
        $user = User::where( 'email', $request->email )->first();
        if ( !is_null( $user ) || !empty( $user ) ) {
            $rules = [
                'password' => 'required|min:6',
                're_password' => 'required|same:password',
            ];

            $input = $request->all();

            $validator = Validator::make( $input, $rules, [
                'required' => ':attribute không được để trống',
                'min' => ':attribute quá ngắn',
                'same' => ':attribute phải giống password',
            ], [
                'password' => 'password',
                're_password' => 're_password',
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
            } else {
                $user->update( [
                    'password' => bcrypt( $request->password ),
                ] );
                return response()->json( [
                    'status' => 'success',
                ] );
            }

        }
    }

    public function UpdateProfile( Request $request ) {
        $validator =  Validator::make( $request->all(), [
            'ho_ten'        =>  'required',
            'sdt'           =>  'required|min:10|max:10',
            'dia_chi'                =>  'required',
        ], [
            'required'      =>  ':attribute không được để trống',
            'max'           =>  ':attribute phải đúng 10 số',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
            'min'           =>  ':attribute phải đúng 10 số'
        ], [
            'id_san_pham'      =>  'sản phẩm',
            'id_mau'     =>  'màu',
            'id_size'   =>  'size',
            'sl'   =>  'số lượng',
            'is_open'      =>  'Tình trạng',
        ] );
        $id = auth()->user()->id;
        $user = User::find( $id );
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
        $user->update( [
            'fullname' => $request->ho_ten,
            'sdt' => $request->sdt,
            'dia_chi' => $request->dia_chi,
        ] );

        return response()->json( [
            'status' => 'success',
            'data' => $user,
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

    public function DonHang( Request $request, $type ) {
        $validator =  Validator::make( $request->all(), [
            'email'        =>  'required|email',
            'nguoi_nhan'      =>   'required',
            'sdt'           =>  'required|min:10|max:10',
            'dia_chi'                =>  'required',
        ], [
            'required'      =>  ':attribute không được để trống',
            'max'           =>  ':attribute phải đúng 10 chữ số',
            'min'           =>  ':attribute phải đúng 10 chữ số',
            'unique'        =>  ':attribute đã tồn tại',
        ],[
            'email'      =>  'email',
            'nguoi_nhan'     =>  'người nhận',
            'sdt'   =>  'số điện thoại',
            'dia_chi'   =>  'địa chỉ',
        ] );
        if ( $validator->fails() ) {

            $error=array();
            $danh_sach_loi = $validator->errors()->messages();
            foreach ( $danh_sach_loi as  $key=>$value ) {
                array_push($error,$value);
            }
            return response()->json([
                'status'=>'error',
                'errorsp'=>$error,
            ]);
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
            if ( $type == 1 ) {
                return $this->vnpay( $request->all() );
            } else {

                // return response()->json([
                //     'adsads'=>$request->all(),
                // ]);
                $donHang = DonHang::create( [
                    'email' =>$request->email,
                    'tong_tien' => $request->tong_tien,
                    'tien_giam_gia' => $request->tien_giam,
                    'thuc_tra' => $request->thuc_tra,
                    'status' => 2,
                    'dia_chi' => $request->dia_chi,
                    'nguoi_nhan' => $request->nguoi_nhan,
                    'sdt' => $request->sdt,
                    'ghi_chu' => $request->ghi_chu,
                    'loai_thanh_toan'=>0,
                ] );
                // dd( $request->don_hang[ 0 ] );
                foreach ( $request->don_hang as $value ) {
                    $chiTietDonHang = ChiTietDonHang::create( [
                        'id_chi_tiet_san_pham' => $value[ 'id_chi_tiet_san_pham' ],
                        'don_gia' => $value[ 'don_gia' ],
                        'so_luong' => $value[ 'so_luong' ],
                        'don_hang_id' => $donHang->id,
                    ] );
                    $chi_tiet_san_pham = ChiTietSanPhamModel::where( 'id', $value[ 'id_chi_tiet_san_pham' ] )->first();
                    $chi_tiet_san_pham->sl_chi_tiet -= $value[ 'so_luong' ];
                    $chi_tiet_san_pham->save();
                }
                // }
                return response()->json( [
                    'status' => 'success',
                    'email' => $donHang->email,
                ] );
            }
        } else {
            return response()->json( [
                'status' => 'error',
                'message'=> 'hãy chọn sản phẩm cần mua',
            ] );
        }
    }

    // public function DonHang( donhangRequest $request ) {
    //     // dd( 'ads' );
    //     if ( count( $request->don_hang ) > 0 ) {
    //         foreach ( $request->don_hang as $value ) {
    //             $san_pham = ChiTietSanPhamModel::find( $value[ 'id_chi_tiet_san_pham' ] );
    //             if ( $value[ 'so_luong' ] > $san_pham->sl_chi_tiet ) {
    //                 return response()->json( [
    //                     'status' => 'error',
    //                     'message' => 'Số lượng trong kho không đủ',
    // ] );
    //             }
    //         }
    //         // if ( $type == 'vnpay' ) {
    //         //     return $this->vnpay( $request->all() );
    //         // } else if ( $type == 'momo' ) {
    //         //     $this->momo( $request->thuc_tra );
    //         // } else {
    //         $donHang = DonHang::create( [
    //             'email' => $request->email,
    //             'tong_tien' => $request->tong_tien,
    //             'tien_giam_gia' => $request->tien_giam,
    //             'thuc_tra' => $request->thuc_tra,
    //             'status' => 2,
    //             'dia_chi' => $request->dia_chi,
    //             'nguoi_nhan' => $request->nguoi_nhan,
    //             'sdt' => $request->sdt,
    //             'ghi_chu' => $request->ghi_chu,
    //             'loai_thanh_toan'=>0,
    // ] );
    //         foreach ( $request->don_hang as $value ) {
    //             $chiTietDonHang = ChiTietDonHang::create( [
    //                 'id_chi_tiet_san_pham' => $value[ 'id_chi_tiet_san_pham' ],
    //                 'don_gia' => $value[ 'don_gia' ],
    //                 'so_luong' => $value[ 'so_luong' ],
    //                 'don_hang_id' => $donHang->id,
    // ] );
    //             $chi_tiet_san_pham = ChiTietSanPhamModel::where( 'id', $value[ 'id_chi_tiet_san_pham' ] )->first();
    //             $chi_tiet_san_pham->sl_chi_tiet -= $value[ 'so_luong' ];
    //             $chi_tiet_san_pham->save();
    //         }
    //         // }
    //         return response()->json( [
    //             'status' => 'success',
    //             'email' => $donHang->email,
    // ] );
    //         // }
    //     } else {
    //         return response()->json( [
    //             'status' => 'error',
    //             'message'=> 'hãy chọn sản phẩm cần mua',
    // ] );
    //     }

    // }
}

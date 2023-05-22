<?php

namespace App\Http\Controllers;

use App\Http\Requests\request as RequestsRequest;
use App\Models\Anh;
use App\Models\User;
// use Flasher\Laravel\Http\Response;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Cookie as FacadesCookie;
use Symfony\Component\HttpFoundation\Cookie as HttpFoundationCookie;
use Illuminate\Support\Facades\Auth;
// use Controller;

class loginController extends Controller {

    public function login( Request $request ) {
        $validator =  Validator::make( $request->all(), [
            'username'      =>  'required||exists:users,username',
            'password'     =>  'required',
        ], [
            'required'      =>  ':attribute không được để trống',
            'unique'        =>  ':attribute không tồn tại'
        ] );

        if ( $validator->fails() ) {
            $error=array();
            $danh_sach_loi = $validator->errors()->messages();
            // dd($danh_sach_loi);
            foreach ( $danh_sach_loi as  $key=>$value ) {
                // echo $key;
                array_push($error,$value);
                // toastr()->error( $value[ 0 ] );
            }
            return response()->json([
                'lỗi'=>$error,
            ]);
            // return redirect()->back();
        }
        if ( !Auth( 'web' )->attempt( $request->only( 'username', 'password' ) ) ) {
            toastr()->error( 'username hoặc password sai!' );
            return redirect()->back();
        }

        $user = User::where( 'username', $request[ 'username' ] )->firstOrFail();

        $token = $user->createToken( 'auth_token' )->plainTextToken;

        if ( $user->id_loai == 0 ) {
            return redirect( '/admin' );
        } else if ( $user->id_loai == 1 ) {
            return redirect( '/nhan-vien' );
        }
    }

    public function home() {
        if ( Auth::guard( 'users' )->check() ) {
            // dd( Auth::guard( 'users' )->user()->id_loai );
            if ( Auth::guard( 'users' )->user()->id_loai == 1 ) {
                return redirect( '/nhan-vien' );
            } else if ( Auth::guard( 'users' )->user()->id_loai == 0 ) {
                return redirect( '/admin' );
            }
        } else {
            return redirect( '/login' );
        }

    }

    public function logout() {
        if ( Auth::check() ) {
            Auth::user()->tokens()->delete();
            Auth::guard( 'web' )->logout();
            return redirect( '/login' );
        } else {
            return response()->json( [
                'status'    => 'erorr',
                'message'   => 'logout fail',
            ] );
        }
    }
}

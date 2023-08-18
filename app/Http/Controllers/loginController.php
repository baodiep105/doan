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

    public function login(Request $request)
    {

        $rules = [
            'username'  => 'required',
            'password'  => 'required',
        ];
        $input     = $request->all();
        $validator = Validator::make($input, $rules, [
            'required'      =>  ':attribute không được để trống',
        ], [
            'username' => 'username',
            'password' => 'password',
        ]);
        if ($validator->fails()) {
            // dd($validator->errors()->messages());
            foreach($validator->errors()->messages() as $value){
                toastr()->error($value[0]);
            }
            return redirect()->back();
        }
        if (!Auth('web')->attempt($request->only('username', 'password'))) {
            toastr()->error("tài khoản hoặc mật khẩu không đúng");
            return redirect()->back();
        }
        $user = User::where('username', $request['username'])->firstOrFail();
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

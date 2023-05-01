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

class loginController extends Controller
{


    public function login(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'username'      =>  'required||exists:users,username',
            'password'     =>  'required',
        ], [
            'required'      =>  ':attribute không được để trống',
            'unique'        =>  ':attribute không tồn tại'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors(),
            ]);
        }
        if (!Auth('web')->attempt($request->only('username', 'password'))) {
            return response()->json([
                'status'=>'erorr',
                'message' => 'Mật khẩu không đúng',
            ], 401);
        }

        $user = User::where('username', $request['username'])->firstOrFail();
        if ($user->is_email == 1) {
            $token = $user->createToken('auth_token')->plainTextToken;

           if($user->id_loai==0){
                return redirect('/admin');
           }else if($user->id_loai==1){
            return redirect('/nhan-vien');
           }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'bạn cần phải kích hoạt mail để login  ',
            ]);
        }
        return response()->json([
            'status' => 'error',
        ]);
    }

    public function check(Request $request){
        dd($request->headers);
        // foreach($request->header() as $value){
        //     echo($value);
        // }
        // dd(Auth::guard('users')->user());


        // return response()->json([
        //         'data'=> Auth::guard('users')->user(),
        //     ]);

    }

    public function home(){
        // dd(isset($_COOKIE['bearer_token']));
        if(Auth::guard('users')->check()){
            // dd(Auth::guard('users')->user()->id_loai);
            if(Auth::guard('users')->user()->id_loai==1){
                return redirect('/nhan-vien');
            }else if(Auth::guard('users')->user()->id_loai==0){
                return redirect('/admin');
            }
        }else{
            return redirect('/login');
        }

    }
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
            Auth::guard("web")->logout();
            $value=$_COOKIE['bearer_token'];
          //   dd($_COOKIE['bearer_token']);
         setcookie('bearer_token',$value,time()-(86400*31),"/");
            return redirect("/login");
        } else {
            return response()->json([
                'status'    => 'erorr',
                'message'   => 'logout fail',
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\donhangRequest;
use App\Http\Requests\profileRequest;
use App\Http\Requests\registerRequest;
use App\Mail\MailKichHoat;
use App\Mail\SendMail;
use App\Models\ChiTietDonHang;
use App\Models\ChiTietSanPhamModel;
use App\Models\DanhGia;
use App\Models\DonHang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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



    public function register(Request $request)
    {
        $rules = [
            'username'  => 'required|unique:users,username',
            'email'     => 'required|unique:users,email',
            'password'  => 'required|min:6',
            're_password' => 'required|same:password'
        ];

        $input     = $request->all();

        $validator = Validator::make($input, $rules,[
            'required'      =>  ':attribute không được để trống',
            'min'           =>  ':attribute quá ngắn',
            'unique'        =>  ':attribute đã tồn tại',
            'numeric'       =>  ':attribute phải là số',
            'same'       =>  ':attribute phải giống password',
        ],[
            'username'=>'username',
            'email'=>'email',
            'password'=>'passwoed',
            're_password'=>'re_password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors(),
            ]);
        }
        $username = $request->username;
        $email    = $request->email;
        $password = $request->password;
        $hash     = Str::uuid();
        $user     = User::create(['username' => $username, 'email' => $email, 'password' => bcrypt($password), 'id_loai' => 2, 'hash' => $hash, 'is_email' => 0]);

        Mail::to($request->email)->send(new SendMail(
            $request->username,
            $hash,
            'Kích Hoạt Tài Khoảng Người Dùng',
        ));
        if ($user) {
            return response()->json([
                'status' => 'success',
            ]);
        }
    }

    public function active($hash)
    {
        $user = User::where('hash', $hash)->first();
        if ($user->is_email) {
            return "<h1>Tài khoản của bạn đã được kích hoạt trước đó</h1>";
        } else {
            $user->is_email = 1;
            $user->save();
            // $a="https://ab05-2402-800-629c-4cf2-5c25-9975-d834-68bf.ap.ngrok.io/login";
            return "<h1>Tài khoản của bạn đã được kích hoạt!</h1> <a href='https://15e8-2402-9d80-439-7544-400d-2590-5e4f-fb98.ap.ngrok.io/login'><h3>Đăng nhập tại đây</h3></a>";
        }
    }
    public function login(Request $request)
    {
        $rules = [
            'username'  => 'required',
            'password'  =>'required',
        ];

        $input     = $request->all();

        $validator = Validator::make($input, $rules,[
            'required'      =>  ':attribute không được để trống',
        ],[
            'username'=>'username',
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
        if ($user->is_email == 1 && $user->id_loai==2) {
            $token = $user->createToken('auth_token')->plainTextToken;
            // dd(Auth::guard('web')->user());
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
            ]);
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


    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
            return response()->json([

                'status'    => 'success',
                'message'   => 'User Logout',
            ], 200);
        } else {
            return response()->json([
                'status'    => 'erorr',
                'message'   => 'logout fail',
            ]);
        }
    }


    public function getme(Request $request)
    {
        // dd($request->headers);
        return response()->json([
            'user' => Auth::guard('users')->user(),
        ]);
    }

    public function danhgiaUser($id, Request $request)
    {
        $rules = [
            'content'  => 'required|min:3',
            'sao' => 'required|numeric',
        ];

        $input     = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()]);
        }
        $danh_gia = DanhGia::create([
            'content'   => $request->content,
            'rate'       => $request->sao,
            'email' => auth()->user()->email,
            'id_san_pham' => $id,
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $danh_gia,
        ]);
    }

    // public function forget_password(Request $request){
    //     $rules = [
    //         'email'     => 'required|exists:users,email',
    //     ];
    //     $input     = $request->all();
    //     $validator = Validator::make($input, $rules);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'error' => $validator->errors()]);
    //     }
    //     $email=$request->email;
    //     $user=User::where('email',$email)->first();

    //     if($user){
    //         $hash=Str::random(6);
    //         $user->reset_password=$hash;
    //         $user->save();
    //         $username=$user->username;
    //         Mail::to($request->email)->send(new orget
    //             $username,
    //             $hash,
    //             'Đổi mật Khẩu Tài Khoảng Người Dùng',
    //         ));
    //     }

    //     if ($user) {
    //         return response()->json([
    //             'status' => 'success',
    //             'data'=>    $request->email,
    //         ]);
    //     }
    // }

    public function xac_nhan(Request $request){
        $user=User::where('reset_password',$request->code);

        if($user){
            return response()->json([
                'status'=>'success',
            ]);
        }
        return response()->json([
            'status'=>'false',
            'message'=>'wrong code!!!'
        ]);
    }

    public function reset_password(Request $request){
        $user=User::where('email',$request->email);
        if($user==true){
            $rules = [
                'password'  => 'required|min:6',
                're_password' => 'required|same:password'
            ];

            $input     = $request->all();

            $validator = Validator::make($input, $rules,[
                'required'      =>  ':attribute không được để trống',
                'min'           =>  ':attribute quá ngắn',
                'same'       =>  ':attribute phải giống password',
            ],[
                'password'=>'password',
                're_password'=>'re_password'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'error' => $validator->errors(),
                ]);
            }else{
                $user->update([
                    'password'    => bcrypt($request->password),
                ]);
                return response()->json([
                    'status'=>'success',
                ]);
            }

        }
    }

    public function UpdateProfile(profileRequest $request)
    {

        $id = auth()->user()->id;
        $user = User::find($id);

        $user->update([
            'fullname'    => $request->ho_ten,
            'sdt'   => $request->sdt,
            'dia_chi'   => $request->dia_chi,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function DonHang(Request $request)
    {
        $validator = Validator::make([
            $request->all()
        ], [
            'nguoi_nhan'      =>   'required',
            'sdt'           =>  'required|min:10|max:10',
            'dia_chi'                =>  'required',
        ], [
            'required'      =>  ':attribute không được để trống',
            'max'           =>  ':attribute quá dài',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
        ], [
            'nguoi_nhan'     =>  'người nhận',
            'sdt'   =>  'số điện thoại',
            'dia_chi'   =>  'địa chỉ',
        ]);
        foreach ($request->don_hang as $value) {
            $san_pham=ChiTietSanPhamModel::find($value['id_chi_tiet_san_pham']);
            if($value['so_luong']>$san_pham->sl_chi_tiet){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Số lượng trong kho không đủ',
                ]);
            }
        }
        if ($request->don_hang > 0) {
            $donHang = DonHang::create([
                'email' => auth()->user()->email,
                'tong_tien' => $request->tong_tien,
                'tien_giam_gia' => $request->tien_giam,
                'thuc_tra' => $request->thuc_tra,
                'status' => 2,
                'dia_chi' => $request->dia_chi,
                'nguoi_nhan' => $request->nguoi_nhan,
                'sdt' => $request->sdt,
                'ghi_chu' => $request->ghi_chu,
            ]);
            foreach ($request->don_hang as $value) {
                $chiTietDonHang = ChiTietDonHang::create([
                    'id_chi_tiet_san_pham' => $value['id_chi_tiet_san_pham'],
                    'don_gia' => $value['don_gia'],
                    'so_luong' => $value['so_luong'],
                    'don_hang_id' => $donHang->id,
                ]);
                $chi_tiet_san_pham=ChiTietSanPhamModel::where('id',$value['id_chi_tiet_san_pham'])->first();
                $chi_tiet_san_pham->sl_chi_tiet-= $value['so_luong'];
                $chi_tiet_san_pham->save();
            }

            return response()->json([
                'status' => 'success',

            ]);
        }
        return response()->json([
            'status'=>'success',
            'message'=>'giỏ hàng rỗng',
        ]);
    }
}

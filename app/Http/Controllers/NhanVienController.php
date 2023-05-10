<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Role;
use Users;

class NhanVienController extends Controller
{
    public function index()
    {
        return view('admin.ql_nhan_vien');
    }

    public function getData()
    {
        $user = User::where('id_loai', 1)->orWhere('id_loai', 0)->paginate(8);
        return response()->json([
            'user' => $user,
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'username'  => 'required|unique:users,username',
            'password'  => 'required|min:6',
            're_password' => 'required|same:password',
            'role' => 'required'
        ];
        $input     = $request->all();
        $validator = Validator::make($input, $rules,[
            'required'      =>  ':attribute không được để trống',
            'min'           =>  ':attribute quá từ 6 ký tự trở lên',
            'unique'        =>  ':attribute đã tồn tại',
            'numeric'       =>  ':attribute phải là số',
            'same'       =>  ':attribute phải giống password',
        ],[
            'username'=>'username',
            'password'=>'password',
            're_password'=>'re-password'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()]);
        }
        $username = $request->username;
        $password = $request->password;
        $role = $request->role;
        $user     = User::create(['username' => $username, 'password' => bcrypt($password), 'id_loai' => $role, 'is_email'=>1]);

        if ($user) {
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function login(Request $request)
    {
        // dd(true);
        if (!Auth('web')->attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('username', $request['username'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
            ]);

        return response()->json([
            'status' => 'error',
        ]);
    }
    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status'  =>  false,
            ]);
        } else {
            $user->delete();
            return response()->json(['status' => true]);
        }
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $rules = [
                'password'  => 'required|min:6',
                're_password' => 'required|same:password'
            ];

            $input     = $request->all();

            $validator = Validator  ::make($input, $rules,[
                'required'      =>  ':attribute không được để trống',
                'min'           =>':attribute phải lớn hơn 6 ký tự',
                'same'       =>  ':attribute phải giống password',
            ],[
                'password'=>'password',
                're_password'=>'re_password'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'error' => $validator->errors()]);
            }

            $password = $request->password;
            $user->update([
                'password' => bcrypt($password),
            ]);
            return response()->json([
                'status' => true,

            ]);
        }
    }
    public function search(Request $request)
    {
        if ($request->all() == null) {
            $data = user::all();
        }
        $data = User::where('username', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%')
            ->get();

        return response()->json(['data' => $data]);
    }
}

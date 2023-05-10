<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class qluserController extends Controller
{
    public function index()
    {
        return view('admin.ql_user');
    }

    public function getData()
    {
        $user = User::where('id_loai', 2)->paginate(6);
        return response()->json([
            'user' => $user,
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            // $san_pham->is_block = !$san_pham->is_open;
            if ($user->is_block == 1) {
                $user->is_block = 0;
            } else {
                $user->is_block = 1;
            }
            $user->save();
            return response()->json(['status' => true]);
        }
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
    public function search(Request $request)
    {
        // dd($request->search);
        $search=$request->search;
        if($search==""){
            $data=User::where('id_loai',2)->get();
        }else{
        $data = User::where('id_loai', 2)->where(function ($query) use($search){
            $query->where('username', 'like', '%' .$search. '%');
            $query->orWhere('email', 'like', '%' . $search . '%');
        })->get();
        }
        return response()->json(['data' => $data]);
    }
}

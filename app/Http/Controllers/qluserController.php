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
        $user = User::where('id_loai', 2)->orderBy('created_at','DESC')
        ->paginate(6);
        return response()->json([
            'user' => $user,
        ]);
    }

    public function changeStatus(Request $request)
    {
        if(!empty($id)){
            $user = User::find($request->id);
            if (!$user) {
               return response()->json([
                'status'=>false,
               ]);
            }else{
                 if ($user->is_block == 1) {
                    $user->is_block = 0;
                } else {
                    $user->is_block = 1;
                }
                $user->save();
                return response()->json(['status' => true]);
            }
        }
        return response()->json([
            'status'=>false,
           ]);

    }

    public function delete($id)
    {
        if(!empty($id)){
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
        return response()->json([
            'status'  =>  false,
        ]);

    }
    public function search(Request $request)
    {
        // dd($request->search);
        $search=$request->search;
        if(empty($search)){
            $data=User::where('id_loai',2)->orderBy('created_at','DESC')->get();
        }else{
        $data = User::where('id_loai', 2)->where(function ($query) use($search){
            $query->where('username', 'like', '%' .$search. '%');
            $query->orWhere('email', 'like', '%' . $search . '%');
        })->orderBy('created_at','DESC')->get();
        }
        return response()->json(['data' => $data]);
    }
}

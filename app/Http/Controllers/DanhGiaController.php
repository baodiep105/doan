<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DanhGiaController extends Controller
{
    public function index(){
        return view('nhan_vien.danh_gia');
    }

   public function getData(){
        $all=DanhGia::all();
        $non_rep=DanhGia::where('children_content',NULl)->orderBy('created_at','DESC')->get()->toArray();
        $items =DanhGia::whereNotIn('id',function($query) {
                $query->select('id')->from('danh_gias')->where('children_content',NULL);})->get();
        return response()->json([
            'status'=>'success',
            'all'=>$all,
            'non_rep'=>$non_rep,
            'reply'=>$items,
        ]);
   }
   public function getDanhGia($id){
        $danh_gia=DanhGia::find($id);
        return response()->json([
            'status'=>'success',
            'data'=>$danh_gia,
        ]);
   }
   public function reply($id,Request $request){
        $danh_gia=DanhGia::find($id);
        $danh_gia->children_content=$request->reply;
        $danh_gia->save();
        return response()->json([
            'status'=>'success',
            'data'=>$danh_gia,
        ]);
   }
   public function delete($id){
        $danh_gia=DanhGia::find($id);
        if(!$danh_gia){
            return response()->json([
                'status'    =>false,
            ]);
        }
            $danh_gia->delete();
            return response()->json([
                'status' =>true,
            ]);
   }
}

<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DanhGiaController extends Controller
{
    public function index()
    {
        return view('admin.danh_gia');
    }

    public function getData()
    {
        $all = DB::table('danh_gias')->join('san_phams', 'danh_gias.id_san_pham', 'san_phams.id')->select('san_phams.ten_san_pham', 'danh_gias.*')->orderBy('created_at', 'DESC')->get();
        $items = DB::table('danh_gias')->join('san_phams', 'danh_gias.id_san_pham', 'san_phams.id')->select('san_phams.ten_san_pham', 'danh_gias.*')->where('children_content', '<>', NULl)->orderBy('updated_at', 'DESC')->get()->toArray();
        $non_rep = DB::table('danh_gias')->join('san_phams', 'danh_gias.id_san_pham', 'san_phams.id')->whereNotIn('danh_gias.id', function ($query) {
            $query->select('id')->from('danh_gias')->where('children_content', '<>', NULl);
        })->orderBy('updated_at', 'DESC')->select('danh_gias.*', 'san_phams.ten_san_pham')->get();
        return response()->json([
            'status' => 'success',
            'all' => $all,
            'non_rep' => $non_rep,
            'reply' => $items,
        ]);
    }
    public function getDanhGia($id)
    {
        $danh_gia = DanhGia::find($id);
        if (!empty($danh_gia)) {
            return response()->json([
                'status' => true,
                'data' => $danh_gia,
            ]);
        }
        return response()->json([
            'status' => false,
        ]);
    }
    public function reply($id, Request $request)
    {
        $danh_gia = DanhGia::find($id);
        if (!empty($danh_gia)) {
            $danh_gia->children_content = $request->reply;
            $danh_gia->save();
            return response()->json([
                'status' => true,
                'data' => $danh_gia,
            ]);
        }
        return response()->json([
            'status' => false,
        ]);
    }
    public function delete($id)
    {
        $danh_gia = DanhGia::find($id);
        if (!$danh_gia) {
            return response()->json([
                'status'    => false,
            ]);
        }
        $danh_gia->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}

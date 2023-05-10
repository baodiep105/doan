<?php

namespace App\Http\Controllers;

use App\Http\Requests\mauRequest;
use App\Models\MauSacModel;
use Illuminate\Http\Request;

class mauController extends Controller
{

    public function getData()
    {
        $mau = MauSacModel::orderBy('created_at', 'DESC')->paginate(8);
        return response()->json([
            'mau'  => $mau,
        ]);
    }
    public function create(mauRequest $request)
    {
        $data = MauSacModel::create([
            'ten_mau' => $request->ten_mau,
            'hex' => $request->ma_mau,
        ]);
        return response()->json([
            'trangThai' =>  true,
            'data' => $data,
        ]);
    }
    public function delete($id)
    {
        $mau = MauSacModel::find($id);
        if (!$mau) {
            return response()->json([
                'status'  =>  false,
            ]);
        } else {
            $mau->delete();
            return response()->json([
                'status'  =>  true,
            ]);
        }
    }
}

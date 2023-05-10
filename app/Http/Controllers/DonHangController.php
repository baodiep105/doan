<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonHangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.ql_don_hang');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData()
    {
        $donhang = DB::table('don_hangs')
            ->leftjoin('users', 'don_hangs.email', 'users.email')
            ->select('don_hangs.*', 'users.username')
            ->paginate(8);

        return response()->json([
            'donhang' => $donhang,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id, Request $request)
    {
        $donhang = DonHang::find($id);
        if (!$donhang) {
            $donhang->status = $request->value;
            $donhang->save();
            return response()->json(['status' => true]);
        }
        return response()->json([
            'status' => false
        ]);
    }

    public function delete($id)
    {
        $donHang = DonHang::find($id);

        if (!$donHang) {
            return response()->json([
                'status'  =>  false,
            ]);
        } else {
            $donHang->delete();
            $chiTietDonHang = ChiTietDonHang::where('don_hang_id', $id)->delete();
            return response()->json(['status' => true]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DonHang  $donHang
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {

        $chitietdonhang = DB::table('chi_tiet_san_pham')
            ->join('chi_tiet_don_hangs', 'chi_tiet_san_pham.id', 'chi_tiet_don_hangs.id_chi_tiet_san_pham')
            ->join('san_phams', 'chi_tiet_san_pham.id_sanpham', 'san_phams.id')
            ->join('mau_sac', 'chi_tiet_san_pham.id_mau', 'mau_sac.id')
            ->join('size', 'chi_tiet_san_pham.id_size', 'size.id')
            ->join('don_hangs', 'chi_tiet_don_hangs.don_hang_id', 'don_hangs.id')
            ->where('don_hang_id', $id)
            ->select('chi_tiet_don_hangs.*', 'chi_tiet_san_pham.*', 'san_phams.ten_san_pham', 'mau_sac.ten_mau', 'size.size',)
            ->get();
        $total = 0;
        foreach ($chitietdonhang as $ey => $value) {
            $total += $value->so_luong * $value->don_gia;
        }

        return view('admin.chi_tiet_hoa_don', compact('chitietdonhang', 'total'));
    }

    public function search(Request $request)
    {
        if ($request->all() == null) {
            $data = DB::table('don_hangs')
                ->join('users', 'don_hangs.email', 'users.email')
                ->select('don_hangs.*', 'users.username')
                ->get();
        } else {
            $data = DB::table('don_hangs')
                ->select('don_hangs.*')
                ->Where('email', 'like', '%' . $request->search . '%')
                ->orWhere('sdt', 'like', '%' . $request->search . '%')
                ->orWhere('created_at', 'like', '%' . $request->search . '%')
                ->get();
        }
        return response()->json(['data' => $data]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DonHang  $donHang
     * @return \Illuminate\Http\Response
     */
    public function destroy(DonHang $donHang)
    {
        //
    }
}

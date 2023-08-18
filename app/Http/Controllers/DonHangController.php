<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

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
            ->orderBy('created_at', 'DESC')
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
        if (!empty($id) && !empty($request->value)) {
            $donhang = DonHang::find($id);
            if (!$donhang) {
                return response()->json([
                    'status' => false
                ]);
            } else {
                $donhang->status = $request->value;
                $donhang->save();
                return response()->json(['status' => true]);
            }
        }
        return response()->json([
            'status' => false
        ]);
    }

    public function delete($id)
    {
        if(!empty($id)){
            $donHang = DonHang::find($id);
            if (!$donHang) {
                return response()->json([
                    'status'  =>  false,
                ]);
            } else {
                DB::table('chi_tiet_don_hangs')->where('don_hang_id',$id)->delete();
                DB::table('don_hangs')->where('id', $id)->delete();
                return response()->json(['status' => true]);
            }
        }
        return response()->json([
            'status'  =>  false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DonHang  $donHang
     * @return \Illuminate\Http\Response
     */
 
    public function detail($id)
    {
        $sql='SELECT `chi_tiet_don_hangs`.`id`,`chi_tiet_don_hangs`.`don_gia`,`chi_tiet_don_hangs`.`so_luong`,`mau_sac`.`hex`,`mau_sac`.`ten_mau`,`size`.`size`,`san_phams`.ten_san_pham,`chi_tiet_san_pham`.id_sanpham,`chi_tiet_don_hangs`.don_gia*`chi_tiet_don_hangs`.so_luong as `total`,`don_hangs`.`tong_tien`,`don_hangs`.`thuc_tra`  FROM `chi_tiet_don_hangs` JOIN `chi_tiet_san_pham` ON `chi_tiet_don_hangs`.id_chi_tiet_san_pham= `chi_tiet_san_pham`.id JOIN `san_phams` on `chi_tiet_san_pham`.id_sanpham = `san_phams`.id
        JOIN `mau_sac` on `chi_tiet_san_pham`.id_mau = `mau_sac`.id
        JOIN `size` on `chi_tiet_san_pham`.id_size = `size`.id
        JOIN `don_hangs` ON `chi_tiet_don_hangs`.`don_hang_id`= `don_hangs`.`id`
        Where `chi_tiet_don_hangs`.don_hang_id='.$id;
        $chiTietDonHang=DB::select($sql);
        $this->getAnh($chiTietDonHang);
        return view('admin.chi_tiet_hoa_don', compact('chiTietDonHang'));
    }

    public function search(Request $request)
    {
        if (empty($request->search)) {
            $data = DB::table('don_hangs')
                ->join('users', 'don_hangs.email', 'users.email')
                ->select('don_hangs.*', 'users.username')
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $data = DB::table('don_hangs')
                ->select('don_hangs.*')
                ->Where('email', 'like', '%' . $request->search . '%')
                ->orWhere('sdt', 'like', '%' . $request->search . '%')
                // ->orWhere( 'created_at', 'like', '%' . $request->search . '%' )
                ->orderBy('created_at', 'DESC')
                ->get();
        }
        return response()->json(['data' => $data]);
    }

}

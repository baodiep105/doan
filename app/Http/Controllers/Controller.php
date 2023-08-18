<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getAnh($san_pham){
        $hinh_anh = DB::table('hinh_anh')->get();
        $arr=array();
        foreach ($san_pham as $key) {
            foreach ($hinh_anh as $value) {
                if ($key->id_sanpham == $value->id_san_pham) {
                    $key->hinh_anh = $value->hinh_anh;
                    array_push($arr,$key);
                    break;
                }
            }
        }
        return $arr;
    }
}

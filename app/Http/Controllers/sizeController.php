<?php

namespace App\Http\Controllers;

use App\Http\Requests\sizeRequest;
use App\Models\sizeModel;
use Illuminate\Http\Request;
use Size;
use Illuminate\Support\Facades\DB;

class sizeController extends Controller {
    public function index() {
        return view( 'admin.size' );
    }

    public function getData() {
        $size = sizeModel::orderBy( 'created_at', 'DESC' )->paginate( 10 );
        return response()->json( [
            'size'  => $size,
        ] );
    }

    public function create( sizeRequest $request ) {
        $data = sizeModel::create( [
            'size' => $request->size,
        ] );
        return response()->json( [
            'trangThai' =>  true,
            'data' => $data,
        ] );
    }

    public function delete( $id ) {
        $size = sizeModel::find( $id );
        if ( !$size ) {
            return response()->json( [
                'status'  =>  false,
            ] );

        } else {
            DB::table( 'size' )->leftJoin( 'chi_tiet_san_pham', 'size.id', 'chi_tiet_san_pham.id_size' )->where( 'size.id', $id )->delete();
            return response()->json( [
                'status'  =>  true,

            ] );
        }
    }
}

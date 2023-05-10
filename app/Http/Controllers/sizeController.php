<?php

namespace App\Http\Controllers;

use App\Http\Requests\sizeRequest;
use App\Models\sizeModel;
use Illuminate\Http\Request;
use Size;

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
        if ( $size ) {
            $size->delete();
            return response()->json( [
                'status'  =>  true,

            ] );
        } else {
            return response()->json( [
                'status'  =>  false,
            ] );
        }
    }
}

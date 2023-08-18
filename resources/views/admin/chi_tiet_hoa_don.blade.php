@extends('master')
@section('title')
    <h1>Chi tiết hóa đơn</h1>
@endsection
@section('content')
    <form action="">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hình Ảnh</th>
                    <th scope="col">Sản Phẩm</th>
                    <th scope="col">Màu</th>
                    <th scope="col">Size</th>
                    <th scope="col">Số Lượng</th>
                    <th scope="col">Đơn giá</th>
                    <th scope="col">tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chiTietDonHang as $key => $value)
                    <tr>
                        <th style="width:3%" scope="row">{{ $key + 1 }}</th>
                        <td><img src="{{ $value->hinh_anh }}" alt="" style="width:50px; height: 50px; "> </td>
                        <td style="width:500px" >{{ $value->ten_san_pham }}</td>
                        <td style="width: 10px;height:10px;background:{{$value->hex}}"></td>
                        <td>{{$value->size}}</td>
                        <td>x{{ $value->so_luong }}</td>
                        <td>{{ $value->don_gia }}</td>
                        <td>{{ $value->so_luong * $value->don_gia }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th scope="row">
                        <h3>total:</h3>
                    </th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{$chiTietDonHang[0]->thuc_tra}}</td>
                </tr>
            </tbody>
        </table>
    </form>
@endsection

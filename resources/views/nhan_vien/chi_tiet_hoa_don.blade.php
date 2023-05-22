@extends('nhan_vien.master')
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
                    <th scope="col">Số Lượng</th>
                    <th scope="col">Đơn giá</th>
                    <th scope="col">tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($san_pham as $key => $value)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td><img src="{{ $value->hinh_anh }}" alt="" style="width:20px; height: 20px; "> </td>
                        <td>{{ $value->ten_san_pham }}</td>
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
                    <td>{{ $total }}</td>
                </tr>
            </tbody>
        </table>
    </form>
@endsection

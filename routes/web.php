<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\ChiTietSanPhamController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\DanhMucSanPhamController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\KhuyenMaiController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\mauController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\productController;
use App\Http\Controllers\qlanhController;
use App\Http\Controllers\qluserController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\sizeController;
use App\Http\Controllers\thongKeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\viewNhanVienController;
use App\Models\DonHang;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/bao', [SanPhamController::class, 'test']);

Route::get('/redirect', function(){
    return Socialite::driver('google')->redirect();
});
// Route::get('/check-cookie',[loginController::class,'check']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/auth/me',[UserController::class,'getme']);
    Route::get('/logout',[loginController::class,'logout']);

});
Route::get('/home',[UserController::class,'home']);
Route::get('/login',function(){
    return view('login');
});
Route::post('/login',[loginController::class,'login']);

Route::middleware('staff')->prefix('/nhan-vien')->group( function () {
    Route::get('/', [viewNhanVienController::class, 'main']);
    Route::get('/chi-tiet-san-pham', [viewNhanVienController::class, 'ViewChiTietSanPham']);
    Route::get('/quan-ly-anh', [viewNhanVienController::class, 'ViewQuanLyAnh']);
    Route::get('/them-san-pham', [viewNhanVienController::class, 'ViewThemSanPham']);
    Route::get('/quan-ly-san-pham', [viewNhanVienController::class, 'ViewQuanLySanPham']);
    Route::get('/quan-ly-size-mau', [viewNhanVienController::class, 'ViewSizeMau']);
    Route::get('/quan-ly-danh-muc', [viewNhanVienController::class, 'ViewDanhMuc']);
    Route::get('/quan-ly-banner', [viewNhanVienController::class, 'ViewBanner']);
    Route::get('/quan-ly-khuyen-mai', [viewNhanVienController::class, 'ViewKhuyenMai']);
    Route::get('/quan-ly-user', [viewNhanVienController::class, 'ViewUser']);
    Route::get('/cap-nhat-don-hang', [viewNhanVienController::class, 'ViewCapNhatDonHang']);
    Route::get('/quan-ly-danh-gia', [DanhGiaController::class, 'index']);

});
Route::get('/',[loginController::class,'home']);
Route::get('/create-san-pham', [SanPhamController::class, 'index'])->middleware('all');
Route::middleware('admin')->prefix('/admin')->group( function () {
    Route::get('/', function(){
        return view('master');
    });
    Route::get('/quan-ly-san-pham', [SanPhamController::class, 'show']);
    Route::get('/quan-ly-danh-muc', [DanhMucSanPhamController::class, 'index']);
    Route::get('/chi-tiet-san-pham', [ChiTietSanPhamController::class, 'index']);
    Route::get('/quan-ly-user', [qluserController::class, 'index']);
    Route::get('/quan-ly-don-hang', [DonHangController::class, 'index']);
    Route::get('/quan-ly-nhan-vien', [NhanVienController::class, 'index']);
    Route::get('/quan-ly-anh', [qlanhController::class, 'index']);
    Route::get('/quan-ly-banner', [BannerController::class, 'index']);
    Route::get('/quan-ly-khuyen-mai', [KhuyenMaiController::class, 'index']);
    Route::get('/quan-ly-size-mau', [sizeController::class, 'index']);
    Route::get('/quan-ly-danh-gia', [DanhGiaController::class, 'index']);
});
// Route::post('/login', [NhanVienController::class, 'login']);

Route::middleware('all')->prefix('/admin')->group( function () {
    Route::group(['prefix' => '/danh-muc'], function () {
        // Route::get('/index',[DanhMucSanPhamController::class,'index']);
        Route::post('/create', [DanhMucSanPhamController::class, 'create']);
        Route::get('/getData', [DanhMucSanPhamController::class, 'getData']);
        Route::get('/changeStatus/{id}', [DanhMucSanPhamController::class, 'doiTrangThai']);
        Route::post('/update', [DanhMucSanPhamController::class, 'update']);
        Route::get('/edit/{id}', [DanhMucSanPhamController::class, 'edit']);
        Route::delete('/delete/{id}', [DanhMucSanPhamController::class, 'delete']);
        Route::post('/search', [DanhMucSanPhamController::class, 'search']);
    });
    Route::group(['prefix' => '/san-pham'], function () {
        Route::post('/create', [SanPhamController::class, 'store']);
        Route::get('/getData', [SanPhamController::class, 'getData']);
        Route::get('/changeStatus/{id}', [SanPhamController::class, 'changeStatus']);
        Route::get('/loadData', [SanPhamController::class, 'loadData']);
        Route::post('/update', [SanPhamController::class, 'update']);
        Route::get('/edit/{id}', [SanPhamController::class, 'edit']);
        Route::get('/delete/{id}', [SanPhamController::class, 'delete']);
        Route::post('/search', [SanPhamController::class, 'search']);
    });

    Route::group(['prefix' => '/chi-tiet-san-pham'], function () {
        Route::get('/getData', [ChiTietSanPhamController::class, 'getData']);
        Route::post('/create', [ChiTietSanPhamController::class, 'create']);
        Route::get('/changeStatus/{id}', [ChiTietSanPhamController::class, 'changeStatus']);
        Route::delete('/delete/{id}', [ChiTietSanPhamController::class, 'delete']);
        Route::get('/edit/{id}', [ChiTietSanPhamController::class, 'edit']);
        Route::post('/update', [ChiTietSanPhamController::class, 'update']);
        Route::post('/search', [ChiTietSanPhamController::class, 'search']);
    });

    Route::group(['prefix' => '/quan-ly-user'], function () {

        Route::get('/getData', [qluserController::class, 'getData']);
        Route::post('/changeStatus', [qluserController::class, 'changeStatus']);
        Route::delete('/delete/{id}', [qluserController::class, 'delete']);
        Route::post('/search', [qluserController::class, 'search']);
    });

    Route::middleware('all')->prefix('/quan-ly-don-hang')->group( function () {

        Route::get('/getData', [DonHangController::class, 'getData']);
        Route::put('/changeStatus/{id}', [DonHangController::class, 'changeStatus']);
        Route::delete('/delete/{id}', [DonHangController::class, 'delete'])->middleware('admin');
        Route::get('/chi-tiet/{id}', [DonHangController::class, 'detail']);
        Route::post('/search', [DonHangController::class, 'search']);
    });

    Route::middleware('admin')->prefix('/quan-ly-nhan-vien')->group( function () {
        Route::post('/create', [NhanVienController::class, 'create']);
        Route::get('/getData', [NhanVienController::class, 'getData']);
        Route::post('/update', [NhanVienController::class, 'update']);
        Route::delete('/delete/{id}', [NhanVienController::class, 'delete']);
        Route::post('/search', [NhanVienController::class, 'search']);

    });
    Route::group(['prefix' => '/quan-ly-anh'], function () {
        Route::post('/create', [qlanhController::class, 'create']);
        Route::get('/getData', [qlanhController::class, 'getData']);
        Route::get('/changeStatus/{id}', [qlanhController::class, 'doiTrangThai']);
        Route::post('/update', [qlanhController::class, 'update']);
        Route::get('/edit/{id}', [qlanhController::class, 'edit']);
        Route::delete('/delete/{id}', [qlanhController::class, 'delete']);
        Route::post('/search', [qlanhController::class, 'search']);
    });
    Route::group(['prefix' => '/quan-ly-banner'], function () {

        Route::post('/create', [BannerController::class, 'create']);
        Route::get('/getData', [BannerController::class, 'getData']);
        Route::get('/changeStatus/{id}', [BannerController::class, 'doiTrangThai']);
        Route::post('/update', [BannerController::class, 'update']);
        Route::get('/edit/{id}', [BannerController::class, 'edit']);
        Route::delete('/delete/{id}', [BannerController::class, 'delete']);
        Route::post('/search', [qlanhController::class, 'search']);
    });
    Route::group(['prefix' => '/quan-ly-khuyen-mai'], function () {
        Route::post('/create', [KhuyenMaiController::class, 'create']);
        Route::get('/getData', [KhuyenMaiController::class, 'getData']);
        Route::get('/changeStatus/{id}', [KhuyenMaiController::class, 'doiTrangThai']);
        Route::post('/update', [KhuyenMaiController::class, 'update']);
        Route::get('/edit/{id}', [KhuyenMaiController::class, 'edit']);
        Route::delete('/delete/{id}', [KhuyenMaiController::class, 'delete']);
        Route::post('/search', [KhuyenMaiController::class, 'search']);
    });
    Route::group(['prefix' => '/quan-ly-size'], function () {

        Route::post('/create', [sizeController::class, 'create']);
        Route::get('/getData', [sizeController::class, 'getData']);
        Route::delete('/delete/{id}', [sizeController::class, 'delete']);
        Route::post('/search', [qlanhController::class, 'search']);
    });
    Route::group(['prefix' => '/quan-ly-mau'], function () {
        Route::post('/create', [mauController::class, 'create']);
        Route::get('/getData', [mauController::class, 'getData']);
        Route::delete('/delete/{id}', [mauController::class, 'delete']);
        Route::post('/search', [qlanhController::class, 'search']);
    });
    Route::group(['prefix' => '/quan-ly-danh-gia'], function () {

        Route::get('/getData', [DanhGiaController::class, 'getData']);
        Route::get('/get-danh-gia/{id}', [DanhGiaController::class, 'getDanhGia']);
        Route::put('/reply/{id}', [DanhGiaController::class, 'reply']);
        Route::delete('/delete/{id}', [DanhGiaController::class, 'delete']);

    });
    Route::middleware('admin')->prefix('/thong-ke')->group( function () {
        // Route::get('/index', [thongKeController::class,'index']);
        Route::get('/all', [thongKeController::class, 'thongKe']);
        Route::get('/data', [thongKeController::class, 'DoanhThuchart']);
        Route::get('/customer', [thongKeController::class, 'Customnerchart']);
        Route::get('/product', [thongKeController::class, 'ProductChart']);

    });
    Route::get('/active/{hash}', [UserController::class, 'active']);
});


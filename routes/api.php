<?php

use App\Http\Controllers\add_cartController;
use App\Http\Controllers\API\DanhMucAPIController;
use App\Http\Controllers\DanhMucSanPhamController;
use App\Http\Controllers\detailController;
use App\Http\Controllers\filterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\lich_su_mua_hangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\productController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\UserController;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/google/login', [UserController::class, 'redirect']);
Route::middleware('cors')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [UserController::class, 'getme']);
        Route::post('/auth/logout', [UserController::class, 'logout']);
        Route::post('/auth/yeu-thich', [HomeController::class, 'themYeuThich']);
        Route::get('/auth/yeu-thich', [HomeController::class, 'getYeuThich']);
        Route::delete('/auth/yeu-thich/{id}', [HomeController::class, 'deleteYeu']);
        Route::post('/auth/danh-gia/{id}', [UserController::class, 'danhgiaUser']);
        Route::put('/auth/update-profile', [UserController::class, 'UpdateProfile']);
        Route::get('/auth/lich-su-don-hang', [lich_su_mua_hangController::class, 'getData']);
        Route::post('/auth/don-hang/{type}', [UserController::class, 'DonHang']);
    });

    Route::get('/google/callback', [UserController::class, 'callback']);
    Route::post('/auth/register', [UserController::class, 'register']);
    Route::post('/auth/login', [UserController::class, 'login']);
    Route::get('/active/{hash}', [UserController::class, 'active']);
    Route::post('/forget-password', [UserController::class, 'forget_password']);
    Route::post('/confirm', [UserController::class, 'xac_nhan']);
    Route::post('/reset-password', [UserController::class, 'reset_password']);
    Route::post('/redirect-google/login',[UserController::class,'google_login']);


    Route::group(['prefix' => 'home'], function () {
        Route::get('/arrival', [HomeController::class, 'arrival']);
        Route::get('/product', [HomeController::class, 'product']);
        Route::get('/category', [HomeController::class, 'danhMuc']);
        Route::get('/banner', [HomeController::class, 'banner']);
        Route::get('/best-sell',[HomeController::class, 'BestSell']);
    });
    Route::group(['prefix' => 'search'], function () {
        Route::get('/data',[SearchController::class,'dataProduct']);
        Route::get('/keyword', [SearchController::class, 'search']);
    });
    Route::group(['prefix' => 'filter'], function () {
        Route::get('/data', [filterController::class, 'dataProduct']);
        Route::get('/san-pham', [filterController::class, 'filter']);
    });
    Route::group(['prefix' => 'detail'], function () {
        Route::get('/product/{id}', [detailController::class, 'detail']);
        Route::post('/danh-gia/{id}', [detailController::class, 'danhGia']);
        Route::get('/danh-gia/data/{id}', [detailController::class, 'listDanhGia']);
    });
    Route::group(['prefix' => 'don-hang'], function () {
        Route::post('/create/vnpay', [add_cartController::class, 'DonHang']);
        Route::get('/lich-su-don-hang', [add_cartController::class, 'LichSuDonHang']);
        Route::get('/lich-su-mua-hang/detail/{id}', [add_cartController::class, 'detail']);
    });
    Route::post('vnpay/return', [add_cartController::class, 'ReturnURL']);

});

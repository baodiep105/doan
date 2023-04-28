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
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::group(['middleware' => ['web']], function () {
// });
Route::get('/google/login', [UserController::class, 'redirect']);
Route::middleware('cors')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [UserController::class, 'getme']);
        Route::post('/auth/logout', [UserController::class, 'logout']);
        Route::post('/auth/yeu-thich', [HomeController::c   lass, 'yeuthich']);
        Route::get('/auth/yeu-thich', [HomeController::class, 'yeu']);
        Route::delete('/auth/yeu-thich/{id}', [HomeController::class, 'deleteYeu']);
        Route::post('/auth/danh-gia/{id}', [UserController::class, 'danhgiaUser']);
        // Route::put('/auth/danh-gia/{id}', [UserController::class, 'danhgiaUser']);
        Route::put('/auth/update-profile', [UserController::class, 'UpdateProfile']);
        Route::get('/auth/lich-su-don-hang', [lich_su_mua_hangController::class, 'getData']);
        Route::post('/auth/don-hang', [UserController::class, 'DonHang']);
        // Route::get('/lich-su-mua-hang/detail/{id}',[lich_su_mua_hangController::class,'detail']);
        Route::get('/check', [NhanVienController::class, 'check']);
    });
    // Route::post('/login',[loginController::class,'login']);

    // Route::post('/post/danhmuc',[DanhMucAPIController::class,'createCategory']);
    // Route::get('/get/danhmuc',[DanhMucAPIController::class,'index']);
    Route::get('/google/callback', [UserController::class, 'callback']);
    Route::post('/auth/register', [UserController::class, 'register']);
    Route::post('/auth/login', [UserController::class, 'login']);
    Route::get('/active/{hash}', [UserController::class, 'active']);
    //Route::get('/send-mail', [SendMailController::class, 'sendMail']);
    Route::post('/forget-password', [UserController::class, 'forget_password']);
    Route::post('/confirm', [UserController::class, 'xac_nhan']);
    Route::post('/reset-password', [UserController::class, 'reset_password']);
    Route::post('/login',[loginController::class,'login']);

    // Route::get('/facebook/login', [UserController::class, 'redirect']);

    // Route::get('/facebook/callback', [UserController::class, 'callback']);




    Route::group(['prefix' => 'home'], function () {
        Route::get('/arrival', [HomeController::class, 'arrival']);
        Route::get('/product', [HomeController::class, 'product']);
        Route::get('/category', [HomeController::class, 'danhMuc']);
        Route::get('/banner', [HomeController::class, 'banner']);
        Route::get('/danh-muc/{id}', [filterController::class, 'danhmuc']);
    });
    Route::group(['prefix' => 'search'], function () {
        // Route::get('/data',[SearchController::class,'dataProduct']);
        // Route::get('/filter',[SearchController::class,'locsanpham']);
        Route::get('/keyword', [SearchController::class, 'search']);
        Route::get('/sort/{value}', [SearchController::class, 'sapXep']);
    });
    Route::group(['prefix' => 'filter'], function () {
        Route::get('/data', [filterController::class, 'dataProduct']);
        // Route::get('/filter',[filterController::class,'locsanpham']);
        Route::get('/san-pham', [filterController::class, 'filter']);
        Route::get('/sort/{value}', [SearchController::class, 'sapXep']);
    });
    Route::group(['prefix' => 'detail'], function () {
        Route::get('/product/{id}', [detailController::class, 'detail']);
        Route::post('/danh-gia/{id}', [detailController::class, 'danhGia']);
        Route::get('/danh-gia/data/{id}', [detailController::class, 'listDanhGia']);
        Route::post('/add-cart', [detailController::class, 'addCart']);
    });
    Route::group(['prefix' => 'don-hang'], function () {
        Route::get('/create/{type}', [add_cartController::class, 'DonHang']);
        Route::get('/lich-su-don-hang', [add_cartController::class, 'LichSuDonHang']);
        Route::get('/lich-su-mua-hang/detail/{id}', [add_cartController::class, 'detail']);
    });
    Route::post('/login', [NhanVienController::class, 'login']);
    Route::get('/payment/{type}', [add_cartController::class, 'payment']);
    Route::get('/payment', [add_cartController::class, 'vnpay']);
    Route::get('vnpay/return', [add_cartController::class, 'ReturnURL']);



    Route::get('/test', [HomeController::class, 'test']);
    Route::get('/test/session', [HomeController::class, 'test_session']);


});

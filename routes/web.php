<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
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
Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('products.index');
    }else {
        return redirect()->route('login');
    }
});
// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => 'auth'], function () {
    Route::resource('products',ProductCintroller::class);
});

// 一覧
Route::get('/index',[ProductController::class,'index'])->name('product.index');

Route::get('/search', [ProductController::class, 'search'])->name('product.search');
// 詳細
Route::get('/show/{id}',[ProductController::class,'show'])->name('product.show');
// 編集
Route::get('/edit/{id}',[ProductController::class,'edit'])->name('product.edit');
// 新規登録
Route::get('/create',[ProductController::class,'create'])->name('product.create');
// 作成
Route::post('/store',[ProductController::class,'store'])->name('product.sotre');
// 編集
Route::post('/update/{id}',[ProductController::class,'update'])->name('product.update');
// 削除
Route::post('/destroy/{id}',[ProductController::class,'destroy'])->name('product.destroy');
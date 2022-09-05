<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductAjaxController;
use App\Models\Product;
use Illuminate\Http\Request;
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

Route::get('/', [HomeController::class, 'getFeaturedProducts']);

// Route::get('/', function (Request $request) {
//     $uri = $request->path();
//     $products = Product::latest()->get();
//     return view('welcome',compact(['uri','products']));
// });


Route::resource('products-manage', ProductAjaxController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

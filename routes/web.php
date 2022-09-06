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

Route::get('/', [HomeController::class, 'getFeaturedProducts']); // Public Product listing
Route::resource('products-manage', ProductAjaxController::class); // Manage product session protected by middleware

Auth::routes(); //Authentication default routes

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::get('magic', [App\Http\Controllers\AuthController::class, 'magic']);
    Route::any('logout', [App\Http\Controllers\AuthController::class, 'logout']);
});

Route::group(['middleware' => ['api', 'auth:api']], function ($router) {
    Route::get('ebook.epub', [App\Http\Controllers\BookController::class, 'apiEbook']);
    Route::get('book/{slug}', [App\Http\Controllers\BookController::class, 'apiShow']);
    Route::post('book/{slug}', [App\Http\Controllers\LibraryController::class, 'apiUpdate']);
    Route::post('book/{slug}/buy', [App\Http\Controllers\LibraryController::class, 'apiBuy']);
    Route::get('library', [App\Http\Controllers\LibraryController::class, 'index']);
    Route::post('book/{slug}/a2l', [App\Http\Controllers\LibraryController::class, 'a2l']);
    Route::delete('book/{slug}/r4l', [App\Http\Controllers\LibraryController::class, 'r4l']);
    Route::get('home', [App\Http\Controllers\BookController::class, 'apiIndexHome']);
    Route::get('books', [App\Http\Controllers\BookController::class, 'apiIndex']);
    Route::get('authors', [App\Http\Controllers\AuthorController::class, 'apiIndex']);
    Route::get('subjects', [App\Http\Controllers\CategoryController::class, 'apiIndex']);
    Route::get('publishers', [App\Http\Controllers\PublicationController::class, 'apiIndex']);
    Route::get('ebook/{id}', [App\Http\Controllers\LibraryController::class, 'apiCheck']);
});

Route::any('/payment/sslcommerz/ipn', [App\Http\Controllers\InvoiceController::class, 'paymentSSL']);
Route::any('/subscription/sslcommerz/ipn', [App\Http\Controllers\SubscriptionController::class, 'subscriptionSSL']);
Route::any('/ebook/sslcommerz/ipn', [App\Http\Controllers\EbookController::class, 'ebookSSL']);
Route::any('/fb-feed', [App\Http\Controllers\BookController::class, 'fbFeed']);

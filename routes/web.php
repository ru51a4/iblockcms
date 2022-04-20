<?php

use App\Models\iblock_element;
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

Route::get('/', function () {
    return redirect("/home");

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/home/{id}', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/detail/{id}', [App\Http\Controllers\HomeController::class, 'detail']);

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index']);
Route::get('/admin/addiblock', [App\Http\Controllers\AdminController::class, 'addiblockform']);
Route::get("/admin/{iblock_element}/editelement", [App\Http\Controllers\AdminController::class, 'editelementform']);
Route::post("/admin/{iblock_element}/editelement", [App\Http\Controllers\AdminController::class, 'editelement']);

Route::post('/admin/addiblock', [App\Http\Controllers\AdminController::class, 'addiblock']);
Route::get('/admin/{iblock}/elementlist', [App\Http\Controllers\AdminController::class, 'elementlist']);
Route::get('/admin/{iblock}/iblockedit', [App\Http\Controllers\AdminController::class, 'iblockeditform']);
Route::post('/admin/{iblock}/iblockedit', [App\Http\Controllers\AdminController::class, 'iblockedit']);
Route::post('/admin/{iblock}/propertyadd', [App\Http\Controllers\AdminController::class, 'propertyadd']);
Route::get('/admin/{iblock}/addelement', [App\Http\Controllers\AdminController::class, 'addelementform']);
Route::post('/admin/{iblock}/addelement', [App\Http\Controllers\AdminController::class, 'addelement']);
Route::get('/admin/{iblock}/delete', [App\Http\Controllers\AdminController::class, 'deleteiblock']);

Route::get("/admin/{iblock_element}/deleteelement", [App\Http\Controllers\AdminController::class, 'deleteelement']);

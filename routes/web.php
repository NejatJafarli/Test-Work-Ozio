<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminMainController;
use App\Http\Controllers\AdminRoomController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminBuyOptionController;
use App\Http\Controllers\AdminFinanceController;

use App\Http\Controllers\AdminConfigController;
use App\Http\Controllers\AjaxDataTableController;

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

Route::get('', function () {
    //check if admin is logged in
    if (auth()->guard('admin')->check()) {
        return redirect()->route('adminDashboard');
    }
    return redirect()->route('adminLogin');
});
Route::get('admin', function () {
    //check if admin is logged in
    if (auth()->guard('admin')->check()) {
        return redirect()->route('adminDashboard');
    }
    return redirect()->route('adminLogin');
});




//route admin/login
Route::get('/admin/login', [AdminAuthController::class, 'login'])->name('adminLogin');

Route::post('/admin/login', [AdminAuthController::class, 'postLogin'])->name('adminLoginPost');

//create middleware adminAuthenticated route group for admin
Route::group(['prefix' => 'admin', 'middleware' => 'adminauth'], function () {
    Route::get('/datatable/task-one', [AjaxDataTableController::class, 'datatableTaskOne'])->name('datatableTaskOne');
    Route::get('/task-one', [AdminMainController::class, 'taskOne'])->name('taskOne');
    Route::get('/dashboard', [AdminMainController::class, 'dashboard'])->name('adminDashboard');
    
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('adminLogout');

    Route::group(['prefix' => 'user'], function () {
        //user detail /userid
        Route::get('/{id}', [AdminMainController::class, 'userDetail'])->name('userDetail');
        // datatableUserReceiptsHistory
        Route::get('/datatable/user-receipts-history', [AjaxDataTableController::class, 'datatableUserReceiptsHistory'])->name('datatableUserReceiptsHistory');
    });
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController as AuthC;
use App\Http\Controllers\DashboardController as DashC;
use App\Http\Controllers\AssessmentController as AssManC;
use App\Http\Controllers\MasterData\AssessmentController as MasAssC;
use App\Http\Controllers\MasterData\BranchController as MasBrC;
use App\Http\Controllers\Report\AssessmentController as ReAssC;
use App\Http\Controllers\Report\StaffController as ReSC;

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

Route::get('phpinfo', function () {
    phpinfo();
});


Route::get('login', [AuthC::class, 'loginForm']);
Route::post('login', [AuthC::class, 'login']);
Route::get('logout', [AuthC::class, 'logout']);

Route::get('/', [DashC::class, 'index']);
Route::get('assessment_manager', [AssManC::class, 'index']);

Route::group(['prefix' => 'master_data', 'as' => 'master_data.'], function () {
    Route::group(['prefix' => 'branch', 'as' => 'branch.'], function () {
        Route::get('{id?}', [MasBrC::class, 'index'])->name('view');
        Route::post('create', [MasBrC::class, 'create'])->name('create');
        Route::post('{id}/update', [MasBrC::class, 'update'])->name('update');
        Route::get('{id}/delete', [MasBrC::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'assessment', 'as' => 'assessment.'], function () {
        Route::get('{id?}', [MasAssC::class, 'index'])->name('view');
        Route::post('create', [MasAssC::class, 'create'])->name('create');
        Route::post('{id}/update', [MasAssC::class, 'update'])->name('update');
        Route::get('{id}/delete', [MasAssC::class, 'delete'])->name('delete');
    });
});


Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
    Route::group(['prefix' => 'staff', 'as' => 'staff.'], function () {
        Route::get('staff/{id?}', [ReSC::class, 'index'])->name('view');
        Route::post('staff/create', [ReSC::class, 'create'])->name('create');
        Route::post('staff/{id}/update', [ReSC::class, 'update'])->name('update');
        Route::get('staff/{id}/delete', [ReSC::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'assessment', 'as' => 'assessment.'], function () {
        Route::get('assessment/{id?}', [ReAssC::class, 'index'])->name('view');
        Route::post('assessment/create', [ReAssC::class, 'create'])->name('create');
        Route::post('assessment/{id}/update', [ReAssC::class, 'update'])->name('update');
        Route::get('assessment/{id}/delete', [ReAssC::class, 'delete'])->name('delete');
    });
});

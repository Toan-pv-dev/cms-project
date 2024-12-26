<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboard;
// use App\Http\Middleware\AuthenticatedMiddleware;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\UserCatalogueController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\backend\PostCatalogueController;
use App\Http\Controllers\backend\PostController;

// use App\Http\Middleware\LoginMiddleware;

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');


Route::get('/', [AuthController::class, 'index'])->name('auth.admin')->middleware('login');



// user
Route::group(['prefix' => 'user'], function () {

    Route::post('store', [UserController::class, 'store'])->name('user.store')->middleware('admin');
    Route::get('index', [UserController::class, 'index'])->name('user.index')->middleware('admin');
    Route::get('create', [UserController::class, 'create'])->name('user.create')->middleware('admin');
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware('admin');
    Route::post('update/{id}', [UserController::class, 'update'])->name('user.update')->middleware('admin');
    Route::get('delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware('admin');
    Route::post('destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('admin');
});
//user-catalogue
Route::group(['prefix' => 'user/catalogue'], function () {

    Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store')->middleware('admin');
    Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index')->middleware('admin');
    Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create')->middleware('admin');
    Route::get('edit/{id}', [UserCatalogueController::class, 'edit'])->name('user.catalogue.edit')->middleware('admin');
    Route::post('update/{id}', [UserCatalogueController::class, 'update'])->name('user.catalogue.update')->middleware('admin');
    Route::get('delete/{id}', [UserCatalogueController::class, 'delete'])->name('user.catalogue.delete')->middleware('admin');
    Route::post('destroy/{id}', [UserCatalogueController::class, 'destroy'])->name('user.catalogue.destroy')->middleware('admin');
});

//language
Route::group(['prefix' => 'language'], function () {

    Route::post('store', [LanguageController::class, 'store'])->name('language.store')->middleware('admin');
    Route::get('index', [LanguageController::class, 'index'])->name('language.index')->middleware('admin');
    Route::get('create', [LanguageController::class, 'create'])->name('language.create')->middleware('admin');
    Route::get('edit/{id}', [LanguageController::class, 'edit'])->name('language.edit')->middleware('admin');
    Route::post('update/{id}', [LanguageController::class, 'update'])->name('language.update')->middleware('admin');
    Route::get('delete/{id}', [LanguageController::class, 'delete'])->name('language.delete')->middleware('admin');
    Route::post('destroy/{id}', [LanguageController::class, 'destroy'])->name('language.destroy')->middleware('admin');
});

//post_catalogue
Route::group(['prefix' => 'post/catalogue'], function () {

    Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store')->middleware('admin');
    Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index')->middleware('admin');
    Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create')->middleware('admin');
    Route::get('edit/{id}', [PostCatalogueController::class, 'edit'])->name('post.catalogue.edit')->middleware('admin');
    Route::post('update/{id}', [PostCatalogueController::class, 'update'])->name('post.catalogue.update')->middleware('admin');
    Route::get('delete/{id}', [PostCatalogueController::class, 'delete'])->name('post.catalogue.delete')->middleware('admin');
    Route::post('destroy/{id}', [PostCatalogueController::class, 'destroy'])->name('post.catalogue.destroy')->middleware('admin');
});

// posts
Route::group(['prefix' => 'post/'], function () {

    Route::post('store', [PostController::class, 'store'])->name('post.store')->middleware('admin');
    Route::get('index', [PostController::class, 'index'])->name('post.index')->middleware('admin');
    Route::get('create', [PostController::class, 'create'])->name('post.create')->middleware('admin');
    Route::get('edit/{id}', [PostController::class, 'edit'])->name('post.edit')->middleware('admin');
    Route::post('update/{id}', [PostController::class, 'update'])->name('post.update')->middleware('admin');
    Route::get('delete/{id}', [PostController::class, 'delete'])->name('post.delete')->middleware('admin');
    Route::post('destroy/{id}', [PostController::class, 'destroy'])->name('post.destroy')->middleware('admin');
});


// Ajax
Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location')->middleware('admin');
Route::post('ajax/dashboard/changeStatus', [AjaxDashboard::class, 'changeStatus'])->name('ajax.dashboard.changeStatus')->middleware('admin');
Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboard::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll')->middleware('admin');




// Login
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('dashboard/index', [DashboardController::class, 'index'])->where(['id' => '[0-9]+'])->name('dashboard.index')->middleware('admin');

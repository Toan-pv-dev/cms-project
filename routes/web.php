<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboard;
// use App\Http\Middleware\AuthenticatedMiddleware;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\UserCatalogueController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\AttributeController as AjaxAttribute;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\backend\PostCatalogueController;
use App\Http\Controllers\backend\PostController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\GenerateController;
// use CKSource\CKFinder\Acl\Permission;
use App\Http\Controllers\Backend\ProductCatalogueController;

use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\AttributeCatalogueController;
use App\Http\Controllers\Backend\AttributeController;
//@@use-module@@

// use App\Http\Middleware\LoginMiddleware;

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

Route::get('/', [AuthController::class, 'index'])->name('auth.admin')->middleware('login');



Route::group(['middleware' => ['admin', 'locale']], function () {
    // user
    Route::group(['prefix' => 'user'], function () {

        Route::post('store', [UserController::class, 'store'])->name('user.store');
        Route::get('index', [UserController::class, 'index'])->name('user.index');
        Route::get('create', [UserController::class, 'create'])->name('user.create');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::get('delete/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });
    //user-catalogue
    Route::group(['prefix' => 'user/catalogue'], function () {

        Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store');
        Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index');
        Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create');
        Route::get('edit/{id}', [UserCatalogueController::class, 'edit'])->name('user.catalogue.edit');
        Route::post('update/{id}', [UserCatalogueController::class, 'update'])->name('user.catalogue.update');
        Route::get('delete/{id}', [UserCatalogueController::class, 'delete'])->name('user.catalogue.delete');
        Route::post('destroy/{id}', [UserCatalogueController::class, 'destroy'])->name('user.catalogue.destroy');
        Route::get('permission', [UserCatalogueController::class, 'permission'])->name('user.catalogue.permission');
        Route::post('upDatePermission', [UserCatalogueController::class, 'updatePermission'])->name('user.catalogue.updatePermission');
    });

    //language
    Route::group(['prefix' => 'language'], function () {
        Route::post('store', [LanguageController::class, 'store'])->name('language.store');
        Route::get('index', [LanguageController::class, 'index'])->name('language.index');
        Route::get('create', [LanguageController::class, 'create'])->name('language.create');
        Route::get('edit/{id}', [LanguageController::class, 'edit'])->name('language.edit');
        Route::post('update/{id}', [LanguageController::class, 'update'])->name('language.update');
        Route::get('delete/{id}', [LanguageController::class, 'delete'])->name('language.delete');
        Route::post('destroy/{id}', [LanguageController::class, 'destroy'])->name('language.destroy');
        Route::get('switch/{id}', [LanguageController::class, 'switchBackendLanguage'])->name('language.switch');
        Route::get('{id}/{languageId}/{model}/translate', [LanguageController::class, 'translate'])->name('language.translate');
        Route::post('storeTranslate', [LanguageController::class, 'storeTranslate'])->name('language.storeTranslate');
    });

    //generate
    Route::group(['prefix' => 'generate'], function () {
        Route::post('store', [GenerateController::class, 'store'])->name('generate.store');
        Route::get('index', [GenerateController::class, 'index'])->name('generate.index');
        Route::get('create', [GenerateController::class, 'create'])->name('generate.create');
        Route::get('edit/{id}', [GenerateController::class, 'edit'])->name('generate.edit');
        Route::post('update/{id}', [GenerateController::class, 'update'])->name('generate.update');
        Route::get('delete/{id}', [GenerateController::class, 'delete'])->name('generate.delete');
        Route::post('destroy/{id}', [GenerateController::class, 'destroy'])->name('generate.destroy');
    });

    //post_catalogue
    Route::group(['prefix' => 'post/catalogue'], function () {
        Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store');
        Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index');
        Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create');
        Route::get('edit/{id}', [PostCatalogueController::class, 'edit'])->name('post.catalogue.edit');
        Route::post('update/{id}', [PostCatalogueController::class, 'update'])->name('post.catalogue.update');
        Route::get('delete/{id}', [PostCatalogueController::class, 'delete'])->name('post.catalogue.delete');
        Route::post('destroy/{id}', [PostCatalogueController::class, 'destroy'])->name('post.catalogue.destroy');
    });

    // posts
    Route::group(['prefix' => 'post/'], function () {

        Route::post('store', [PostController::class, 'store'])->name('post.store');
        Route::get('index', [PostController::class, 'index'])->name('post.index');
        Route::get('create', [PostController::class, 'create'])->name('post.create');
        Route::get('edit/{id}', [PostController::class, 'edit'])->name('post.edit');
        Route::post('update/{id}', [PostController::class, 'update'])->name('post.update');
        Route::get('delete/{id}', [PostController::class, 'delete'])->name('post.delete');
        Route::post('destroy/{id}', [PostController::class, 'destroy'])->name('post.destroy');
    });

    Route::group(['prefix' => 'permission/'], function () {

        Route::post('store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('index', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('create', [PermissionController::class, 'create'])->name('permission.create');
        Route::get('edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::post('update/{id}', [PermissionController::class, 'update'])->name('permission.update');
        Route::get('delete/{id}', [PermissionController::class, 'delete'])->name('permission.delete');
        Route::post('destroy/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');
    });



    // ProductCatalogue routes
    Route::group(['prefix' => 'product/catalogue'], function () {
        Route::post('store', [ProductCatalogueController::class, 'store'])->name('product.catalogue.store');
        Route::get('index', [ProductCatalogueController::class, 'index'])->name('product.catalogue.index');
        Route::get('create', [ProductCatalogueController::class, 'create'])->name('product.catalogue.create');
        Route::get('edit/{id}', [ProductCatalogueController::class, 'edit'])->name('product.catalogue.edit');
        Route::post('update/{id}', [ProductCatalogueController::class, 'update'])->name('product.catalogue.update');
        Route::get('delete/{id}', [ProductCatalogueController::class, 'delete'])->name('product.catalogue.delete');
        Route::post('destroy/{id}', [ProductCatalogueController::class, 'destroy'])->name('product.catalogue.destroy');
    });


    // GalleryCatalogue routes


    // ProductCatalogue routes


    // Product routes

    // Product routes
    Route::group(['prefix' => 'product'], function () {
        Route::post('store', [ProductController::class, 'store'])->name('product.store');
        Route::get('index', [ProductController::class, 'index'])->name('product.index');
        Route::get('create', [ProductController::class, 'create'])->name('product.create');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('update/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::get('delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
        Route::post('destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    // AttributeCatalogue routes
    Route::group(['prefix' => 'attribute/catalogue'], function () {
        Route::post('store', [AttributeCatalogueController::class, 'store'])->name('attribute.catalogue.store');
        Route::get('index', [AttributeCatalogueController::class, 'index'])->name('attribute.catalogue.index');
        Route::get('create', [AttributeCatalogueController::class, 'create'])->name('attribute.catalogue.create');
        Route::get('edit/{id}', [AttributeCatalogueController::class, 'edit'])->name('attribute.catalogue.edit');
        Route::post('update/{id}', [AttributeCatalogueController::class, 'update'])->name('attribute.catalogue.update');
        Route::get('delete/{id}', [AttributeCatalogueController::class, 'delete'])->name('attribute.catalogue.delete');
        Route::post('destroy/{id}', [AttributeCatalogueController::class, 'destroy'])->name('attribute.catalogue.destroy');
    });

    // Attribute routes
    Route::group(['prefix' => 'attribute'], function () {
        Route::post('store', [AttributeController::class, 'store'])->name('attribute.store');
        Route::get('index', [AttributeController::class, 'index'])->name('attribute.index');
        Route::get('create', [AttributeController::class, 'create'])->name('attribute.create');
        Route::get('edit/{id}', [AttributeController::class, 'edit'])->name('attribute.edit');
        Route::post('update/{id}', [AttributeController::class, 'update'])->name('attribute.update');
        Route::get('delete/{id}', [AttributeController::class, 'delete'])->name('attribute.delete');
        Route::post('destroy/{id}', [AttributeController::class, 'destroy'])->name('attribute.destroy');
    });
    //@@new-module@@











    Route::get('dashboard/index', [DashboardController::class, 'index'])->where(['id' => '[0-9]+'])->name('dashboard.index');
});



// Ajax
Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location');
Route::post('ajax/dashboard/changeStatus', [AjaxDashboard::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboard::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
Route::get('ajax/attribute/getAttribute', [AjaxAttribute::class, 'getAttribute'])->name('ajax.attribute.getAttribute');


// AuthAuth
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
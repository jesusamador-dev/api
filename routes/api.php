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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * Rutas para el manejo de usuarios
 */
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', ['middleware' => 'auth.role:admin,client', 'uses' => 'AuthController@logout', 'as' => 'logout']);
    Route::post('me', 'AuthController@me');
    Route::post('refresh', 'AuthController@refresh');
});

/**
 * Rutas para los producctos
 */
Route::group([
    'prefix' => 'products'
], function () {
    Route::post('create', ['middleware' => 'auth.role:admin', 'uses' => 'ProductsController@store', 'as' => 'create']);
    Route::post('get/{id}', ['middleware' => 'auth.role:admin,client', 'uses' => 'ProductsController@index', 'as' => 'get']);
    Route::post('edit/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'ProductsController@edit', 'as' => 'edit']);
    Route::post('update/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'ProductsController@update', 'as' => 'update']);
    Route::post('get', ['middleware' => 'auth.role:admin', 'uses' => 'ProductsController@index', 'as' => 'get']);
    Route::post('destroy/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'ProductsController@destroy', 'as' => 'destroy']);
});

/**
 * Rutas paa las categorias
 */
Route::group([
    'prefix' => 'categories'
], function () {
    Route::post('get/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'CategoriesController@index', 'as' => 'get']);
    Route::post('get', ['middleware' => 'auth.role:admin', 'uses' => 'CategoriesController@index', 'as' => 'get']);
    Route::post('create', ['middleware' => 'auth.role:admin', 'uses' => 'CategoriesController@store', 'as' => 'create']);
    Route::post('destroy/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'CategoriesController@destroy', 'as' => 'destroy']);
    Route::post('update/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'CategoriesController@update', 'as' => 'update']);
    Route::post('edit/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'CategoriesController@edit', 'as' => 'edit']);
});

/**
 * Rutas para los departamentos
 */
Route::group([
    'prefix' => 'departments'
], function () {
    Route::get('get/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'DepartmentsController@index', 'as' => 'get']);
    Route::get('get', ['middleware' => 'auth.role:admin', 'uses' => 'DepartmentsController@index', 'as' => 'get']);
    Route::post('create', ['middleware' => 'auth.role:admin', 'uses' => 'DepartmentsController@store', 'as' => 'create']);
    Route::post('destroy/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'DepartmentsController@destroy', 'as' => 'destroy']);
    Route::post('update/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'DepartmentsController@update', 'as' => 'update']);
    Route::post('edit/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'DepartmentsController@edit', 'as' => 'edit']);
});



/**
 * Rutas para las marcas
 */
Route::group([
    'prefix' => 'brands'
], function () {
    Route::post('get/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'BrandsController@index', 'as' => 'get']);
    Route::post('get', ['middleware' => 'auth.role:admin', 'uses' => 'BrandsController@index', 'as' => 'get']);
    Route::post('create', ['middleware' => 'auth.role:admin', 'uses' => 'BrandsController@store', 'as' => 'create']);
    Route::post('destroy/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'BrandsController@destroy', 'as' => 'destroy']);
    Route::post('update/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'BrandsController@update', 'as' => 'update']);
    Route::post('edit/{id}', ['middleware' => 'auth.role:admin', 'uses' => 'BrandsController@edit', 'as' => 'edit']);
});

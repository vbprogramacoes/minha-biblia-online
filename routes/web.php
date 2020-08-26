<?php

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

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Preview route
|--------------------------------------------------------------------------
|
| Route for prismic.io preview functionality
|
*/

Route::get('/preview', function (Request $request) {
    $token = $request->input('token');
    if (!isset($token)) {
        return abort(400, 'Bad Request');
    }
    $url = $request->attributes->get('api')->previewSession($token, $request->attributes->get('linkResolver'), '/');
    return response(null, 302)->header('Location', $url);
});

/*
|--------------------------------------------------------------------------
| Tutorial route
|--------------------------------------------------------------------------
*/

Route::get('/tutorial', function () {
    return view('tutorial');
});

/*
|--------------------------------------------------------------------------
| 404 Page Not Found
|--------------------------------------------------------------------------
*/

Route::get('/{path}', function () {
    return view('404');
});
Route::get('/', 'HomeController@show');
// Get page by UID
Route::get('/biblia', 'BiblesController@home');
Route::get('/biblia/{version}', 'BiblesController@version');
Route::get('/biblia/{version}/{book}', 'BiblesController@book');
Route::get('/biblia/{version}/{book}/{chapter}', 'BiblesController@chapter');
Route::get('/biblia/{version}/{book}/{chapter}/{verse}', 'BiblesController@verse');
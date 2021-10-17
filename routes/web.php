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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::middleware('verified')->group(function() {
//     Route::get('/home', 'HomeController@index')->name('home');

// });
Route::get('/room', 'RoomController@getListAction')->name('room');
Route::post('/room/store', 'RoomController@store');

Route::get('/room/ai-chat', 'ChatController@getAiRoom')->name('room-ai');
Route::get('/room/{room_id}', 'ChatController@getAction');


// Route::prefix('api')->group(function() {
    Route::get('/api/message/get/{room_id}','ChatController@getMessages');
    Route::post('/api/message/store','ChatController@store');
    Route::post('/api/message/delete/{message_id}','ChatController@destroy');
    Route::post('/api/message/edit','ChatController@edit');


// });
Route::get('/account-setting', 'AccountSettingController@getAction')->name('account-setting');
Route::post('/account-setting', 'AccountSettingController@postAction');
Route::post('/account-setting/delete', 'AccountSettingController@deleteAccount');


Route::get('/board', 'ChatController@index')->name('board');
Route::post('/register/pre_check', 'Auth\RegisterController@pre_check')->name('register.pre_check');
Route::post('/register/main_check', 'Auth\RegisterController@mainCheck')->name('register.main.check');
Route::post('/register/main_register', 'Auth\RegisterController@mainRegister')->name('register.main.registered');
Route::get('/register/verify/{token}', 'Auth\RegisterController@showForm');


Route::get('/youtube', 'YoutubeController@getAction')->name('youtube');



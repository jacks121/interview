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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('email/verify/{token}','EmailController@verify')->name('email.verify');

Route::middleware('auth')->get('notifications','NotificationsController@index');

Route::resource('/questions','QuestionsController');

Route::post('questions/{question}/answer','AnswersController@store')->name('questions.answer');

Route::get('my/passport','PassportController@index')->name('my.passport');

Route::middleware('auth')->get('/inbox','InboxController@index');

Route::middleware('auth')->get('/inbox/{dialog_id}','InboxController@show')->name('inbox.show');

Route::middleware('auth')->get('/inbox/{dialog_id}/store','InboxController@store')->name('inbox.store');

Route::post('avatar','UploadImageController@update');

Route::middleware('auth')->get('/edit/avatar','UploadImageController@edit');

Route::get('/project',function(){
    return view('project');
});

Route::get('/mind',function(){
    return view('mind_node');
});

Route::get('/docker',function(){
    return view('docker');
});

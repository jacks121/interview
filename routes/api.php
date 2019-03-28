<?php

use Illuminate\Http\Request;

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

Route::namespace('Api')->middleware('auth:api')->group(function () {
    //问题是否关注
    Route::post('/questions/is_follow', 'QuestionFollowController@isFollow');
    //关注问题
    Route::post('/questions/follow', 'QuestionFollowController@follow');
    //关注用户
    Route::post('/users/follow', 'UserFollowController@follow');
    //是否关注了某个用户
    Route::post('/users/is_follow', 'UserFollowController@isFollow');
    //发送私信
    Route::post('/send_message','MessageController@sendPrivateMessage');
    //添加评论
    Route::post('comments','CommentsController@commentStore');
    //根据问题id 获取相关评论数
    Route::get('comments/count/{type}/{id}','CommentsController@getCommentCount');

    Route::get('messages/{dialog_id}/{offset}/{limit}','MessageController@index');

});

Route::namespace('Api')->middleware('api')->group(function () {

    Route::resource('/topic', 'TopicController');
    //根据回答id 获取回答相关的评论
    Route::get('/comments/{id}/{type}','CommentsController@getComments');

});

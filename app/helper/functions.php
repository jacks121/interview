<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019/3/25
 * Time: 9:57 AM
 */
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Predis\Connection\ConnectionException;
use Illuminate\Support\Facades\Auth;

if ( !function_exists('returnJson') ) {
    /**
     * api返回json数据
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    function returnJson($params)
    {
        return response()->json($params);
    }
}

if ( !function_exists('apiUser') ) {
    /**
     * 获取api user collection
     * @return mixed
     */
    function apiUser()
    {
        return Auth::guard('api')->user();
    }
}

if ( !function_exists('apiUserId') ) {
    /**
     * 获取api user id
     * @return mixed
     */
    function apiUserId()
    {
        return Auth::guard('api')->id();
    }
}

if ( !function_exists('userId') ) {
    /**
     * 获取user id
     * @return mixed
     */
    function userId()
    {
        return Auth::id();
    }
}

if ( !function_exists('user') ) {

    /**
     * 获取user
     * @return mixed
     */
    function user()
    {
        return Auth::user();
    }
}

if ( !function_exists('pushRedisQuestions') ) {

    /**
     * 向redis的questions表中添加一个question
     * @param $id
     * @param $question
     * @return bool
     */
    function pushRedisQuestions($id, $question)
    {
        try{
            return Redis::hset('questions',$id,json_encode($question));
        }catch (ConnectionException $exception){
            Log::error($exception->getMessage());
            return false;
        }
    }
}

if ( !function_exists('getRedisQuestions') ) {

    /**
     * 通过id从redis中获取一条question数据
     * @param $id
     * @return bool|mixed
     */
    function getRedisQuestions($id)
    {
        try{
            return json_decode(Redis::hget('questions',$id));
        }catch (ConnectionException $exception){
            Log::error($exception->getMessage());
            return false;
        }
    }
}


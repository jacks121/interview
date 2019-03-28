<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TopicResource;
use App\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return object $data
     */
    public function index(Request $request)
    {
        $condition = $request->q;
        $data = TopicResource::collection(Topic::where('name','like','%'.$condition.'%')->limit(3)->get());

        return $data;
    }
}

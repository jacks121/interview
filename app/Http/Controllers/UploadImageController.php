<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class UploadImageController
 * @package App\Http\Controllers
 */
class UploadImageController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('uploadAvatar');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function update(Request $request)
    {
        $file = $request->file('img')->store('public/avatars');

        user()->avatar = asset(Storage::url($file));

        user()->save();

        return json_encode(user()->avatar);
    }
}

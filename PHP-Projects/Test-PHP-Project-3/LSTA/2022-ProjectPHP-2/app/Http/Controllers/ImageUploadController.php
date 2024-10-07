<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function imageUploadPost(Request $request): JsonResponse
    {
//        $request->validate([
//            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
//        ]);

        $request->file("photo")->getClientOriginalName();
        $request->file('photo')->store('public/images/');
        $hashName = $request->file('photo')->hashName();

        return response()->json([
            "path" => "$hashName"
        ]);
    }
}

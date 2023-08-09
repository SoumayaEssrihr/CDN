<?php

namespace App\Http\Controllers;

use App\Models\Path;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadImg extends Controller
{
    public function storeImg(Request $request) {
        $validator = Validator::make($request->all(), [
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'userId' => 'required|integer'
        ]);
        if ($validator->fails()) {
        return response()->json(['error' => 'Invalid image. Please upload a valid JPEG, PNG, or JPG image (up to 2MB).'], 400);
        }
        $userId=$request->input('userId');
        $img = $request->file('image');
        $imgPath = '/' . ltrim($img->store('images'), '/');
        if (!$imgPath) {
        return response()->json(['error' => 'Failed to store the image. Please try again.'], 500);
        }
        Path::create([
            'path' => $imgPath,
            'userId' => $userId
        ]);
        $imageUrl = asset(Storage::url(ltrim($imgPath, '/')));
        return response()->json(['message' => 'Image uploaded successfully', 'url' => $imageUrl]);
}

    public function storeImgs(Request $request){
        $validator = Validator::make($request->all(), [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'userId' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid image(s). Please upload a valid image (up to 2MB).'], 400);
        }
        $userId=$request->input('userId');
        $imgs=$request->file('images');
        $imgUrls = [];

        foreach ($imgs as $img) {
            $imgPath = '/' . ltrim($img->store('images'), '/');
            if (!$imgPath) {
            return response()->json(['error' => 'Failed to store the image. Please try again.'], 500);
            }
            Path::create([
                'path' => $imgPath,
                'userId' => $userId
            ]);
            $imgUrls[] = Storage::url($imgPath);
        }
        return response()->json(['message' => 'Image uploaded successfully', 'url' => $imgUrls]);    }
}

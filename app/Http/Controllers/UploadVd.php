<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Path;

class UploadVd extends Controller
{
    public function storeVideo(Request $request) {
        $validator = Validator::make($request->all(), [
        'video' => 'required|file|mimes:mp4,mov,avi|max:256000',
        'userId' => 'required|integer'
        ]);

        if ($validator->fails()) {
        return response()->json(['error' => 'Invalid video. Please upload a valid video file (mp4, mov, or avi) up to 50MB.'], 400);
        }
        $userId=$request->input('userId');
        $video = $request->file('video');
        $videoPath ='/' . ltrim($video->store('videos'), '/');
        if (!$videoPath) {
        return response()->json(['error' => 'Failed to store the video. Please try again.'], 500);
        }
        Path::create([
            'path' => $videoPath,
            'userId' => $userId
        ]);
        $videoUrl = Storage::url($videoPath);
        return response()->json(['message' => 'Video uploaded successfully', 'url' => $videoUrl]);
    }
    
    public function storeVideos(Request $request) {
        $validator = Validator::make($request->all(), [
            'videos' => 'required|array',
            'videos.*' => 'file|mimes:mp4,mov,avi|max:256000',
            'userId' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid video. Please upload a valid video file (mp4, mov, or avi) up to 50MB.'], 400);
        }
        $userId=$request->input('userId');
        $vds = $request->file('videos');
        $vdUrls = [];
        foreach ($vds as $vd) {
            $vdPath ='/' . ltrim($vd->store('videos'), '/');
            if (!$vdPath) {
            return response()->json(['error' => 'Failed to store the video. Please try again.'], 500);
            }
            Path::create([
                'path' => $vdPath,
                'userId' => $userId
            ]);
            $vdUrls[] = Storage::url($vdPath);
        }
        return response()->json(['message' => 'Video uploaded successfully', 'url' => $vdUrls]);
    }
}

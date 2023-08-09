<?php

namespace App\Http\Controllers;

use App\Models\Path;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DeleteFile extends Controller
{
    public function deleteFile(Request $request) {
    $path = $request->get('path');

    if (Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
        $file = Path::where('path', $path)->first();
        if (!$file) {
            return response()->json(['message' => 'The path file not found in the database.',], 404);
        }
        $file->delete();
        return response()->json(['message' => 'The file has been deleted successfully.',], 200);
    }
    else {
        return response()->json(['message' => 'The file not found.',], 404);
    }
    }
}

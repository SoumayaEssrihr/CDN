<?php

namespace App\Http\Controllers;

use App\Models\Path;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadPdf extends Controller{


    public function storePDF(Request $request) {
        $validator = Validator::make($request->all(), [
            'pdf' => 'required|file|mimes:pdf|max:10000',
            'userId' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid PDF. Please upload a valid PDF file (up to 10MB).'], 400);
        }
        $userId=$request->input('userId');
        $pdf = $request->file('pdf');
        $pdfPath = '/' . ltrim($pdf->store('docs'), '/');
        if (!$pdfPath) {
            return response()->json(['error' => 'Failed to store the PDF. Please try again.'], 500);
        }
        Path::create([
            'path' => $pdfPath,
            'userId' => $userId
        ]);
        $pdfUrl = Storage::url($pdfPath);
        return response()->json(['message' => 'PDF uploaded successfully', 'url' => $pdfUrl]);
    }

    public function storePdfs(Request $request) {
        $validator = Validator::make($request->all(), [
            'pdfs' => 'required|array',
            'pdfs.*' => 'file|mimes:pdf|max:10000',
            'userId' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid PDF. Please upload a valid PDF file (up to 10MB).'], 400);
        }
        $userId=$request->input('userId');
        $pdfs = $request->file('pdfs');
        $pdfUrls = [];
        foreach ($pdfs as $pdf) {
            $pdfPath = '/' . ltrim($pdf->store('docs'), '/');
            if (!$pdfPath) {
                return response()->json(['error' => 'Failed to store the PDF. Please try again.'], 500);
            }
            Path::create([
                'path' => $pdfPath,
                'userId' => $userId
            ]);
            $pdfUrls[] = Storage::url($pdfPath);
        }
        return response()->json(['message' => 'PDF uploaded successfully', 'url' => $pdfUrls]);
    }
}

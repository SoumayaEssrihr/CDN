<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UploadVd;
use App\Http\Controllers\UploadImg;
use App\Http\Controllers\UploadPdf;
use App\Http\Controllers\DeleteFile;
use Illuminate\Support\Facades\Route;

Route::Post('/upload-imgs',[UploadImg::class,'storeImgs']);
Route::Post('/upload-vds',[UploadVd::class,'storeVideos']);
Route::Post('/upload-pdfs',[UploadPdf::class,'storePdfs']);
Route::Post('/upload-img',[UploadImg::class,'storeImg']);
Route::Post('/upload-vd',[UploadVd::class,'storeVideo']);
Route::Post('/upload-pdf',[UploadPdf::class,'storePdf']);
Route::Post('/delete-file',[DeleteFile::class,'deleteFile']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

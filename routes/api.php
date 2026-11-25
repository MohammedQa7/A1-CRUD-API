<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hi', function () {
    return [
        'status' => '200',
        'message' => 'Hello world'
    ];
});
Route::get('/', function () {
    return [
        "name" => "Task API",
        "version" => "1.0",
        "endpoints" => ["/tasks"]
    ];
});
Route::get('/health', function () {
    return [
        'status' => 'OK',
    ];
});
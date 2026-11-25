<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hi', function () {
    return response()->json([
        'status' => '200',
        'message' => 'Hello world'
    ]);
});
Route::get('/', function () {
    return response()->json([
        "name" => "Task API",
        "version" => "1.0",
        "endpoints" => ["/tasks"]
    ]);
});
Route::get('/health', function () {
    return [
        'status' => 'OK',
    ];
});


// GET /api/tasks
Route::get('/tasks', function () {

    $tasks = session()->get('tasks', [
        ['id' => 1, 'title' => 'Learn PHP', 'done' => true],
        ['id' => 2, 'title' => 'Learn Laravel', 'done' => false],
        ['id' => 3, 'title' => 'Build a CRUD API', 'done' => false],
    ]);

    session()->put('tasks', $tasks);

    return response()->json($tasks);
});


// GET /api/tasks/{id}
Route::get('/tasks/{id}', function ($id) {

    $tasks = session()->get('tasks', [
        ['id' => 1, 'title' => 'Learn PHP', 'done' => true],
        ['id' => 2, 'title' => 'Learn Laravel', 'done' => false],
        ['id' => 3, 'title' => 'Build a CRUD API', 'done' => false],
    ]);

    session()->put('tasks', $tasks);

    $task = collect($tasks)->firstWhere('id', (int) $id);

    if (!$task) {
        return response()->json([
            'error' => "Task {$id} not found"
        ], 404);
    }

    return response()->json($task);
});
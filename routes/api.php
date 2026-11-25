<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// Starting data, used until a new list is saved in the cache
$defaultTasks = [
    ['id' => 1, 'title' => 'Learn PHP', 'done' => true],
    ['id' => 2, 'title' => 'Learn Laravel', 'done' => false],
    ['id' => 3, 'title' => 'Build a CRUD API', 'done' => true],
];

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hi', function () {
    return response()->json([
        'status' => '200',
        'message' => 'Hello world',
    ]);
});

Route::get('/', function () {
    return response()->json([
        'name' => 'Task API',
        'version' => '1.0',
        'endpoints' => ['/tasks'],
    ]);
});

Route::get('/health', function () {
    return ['status' => 'OK'];
});

// ---------------------------------------

// GET /tasks - return all tasks
Route::get('/tasks', function () use ($defaultTasks) {
    $tasks = Cache::store('file')->get('tasks', $defaultTasks);

    return response()->json($tasks);
});

// GET /tasks/{id} - return one task
Route::get('/tasks/{id}', function (string $id) use ($defaultTasks) {
    $tasks = Cache::store('file')->get('tasks', $defaultTasks);

    // Compare as strings, so "1abc" doesn't match task 1
    $task = collect($tasks)->first(fn($task) => (string) $task['id'] === $id);

    if (!$task) {
        return response()->json(['error' => "Task {$id} not found"], 404);
    }

    return response()->json($task);
});

// POST /tasks - create a task
Route::post('/tasks', function (Request $request) use ($defaultTasks) {
    $title = $request->input('title');

    if (!is_string($title) || trim($title) === '') {
        return response()->json(['error' => 'Title is required'], 400);
    }

    $tasks = Cache::store('file')->get('tasks', $defaultTasks);

    $task = [
        'id' => empty($tasks) ? 1 : max(array_column($tasks, 'id')) + 1,
        'title' => trim($title),
        'done' => false,
    ];

    $tasks[] = $task;
    Cache::store('file')->forever('tasks', $tasks);

    return response()->json($task, 201);
});
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AIChatController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout',[AuthController::class,'logout']);
    Route::put('/profile', [AuthController::class,'update']);
    Route::delete('/profile',[AuthController::class,'destroy']);

    //ai
    Route::get('/ai',[AIChatController::class,'index']);
    Route::get('/ai/{id}',[AIChatController::class,'show']);
    Route::post('/ai',[AIChatController::class,'new_chat']);
    Route::post('/ai/{id}/chat',[AIChatController::class,'chat']);
    Route::delete('/ai/{id}/delete',[AIChatController::class,'destroy']);

    //schedule
    Route::get('/schedule',[ScheduleController::class,'index']);
    Route::get('/schedule/{id}/detail',[ScheduleController::class,'show']);
    Route::post('/schedule',[ScheduleController::class,'store']);
    Route::post('/schedule/{id}/detail',[ScheduleController::class,'storeDetail']);
    Route::put('/schedule/{id}',[ScheduleController::class,'update']);
    Route::put('/schedule/{id}/detail/{id}',[ScheduleController::class,'updateDetail']);
    Route::delete('/schedule/{id}',[ScheduleController::class,'destroy']);

    //forum
    Route::get('/forum',[ForumController::class,'index']);
    Route::get('/forum/{id}',[ForumController::class,'show']);
    Route::post('/forum',[ForumController::class,'store']);
    Route::post('/forum/{id}/comments',[ForumController::class,'storeComment']);
    Route::get('/forum-categories', [ForumController::class, 'category']);
    Route::post('/forum-categories', [ForumController::class, 'storeCategory']);
});
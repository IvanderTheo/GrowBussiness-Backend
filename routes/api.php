<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AIChatController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HppController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::get('/forum-categories', [ForumController::class, 'category']);
Route::get('/forum',[ForumController::class,'index']);
Route::get('/forum/{id}',[ForumController::class,'show']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout',[AuthController::class,'logout']);
    Route::put('/profile', [AuthController::class,'update']);
    Route::delete('/profile',[AuthController::class,'destroy']);

    //ai
    Route::get('/ai',[AIChatController::class,'index']);
    Route::get('/ai/{id}',[AIChatController::class,'show']);
    Route::post('/ai/chat', [AIChatController::class, 'chat']);
    Route::delete('/ai/{id}/delete',[AIChatController::class,'destroy']);
    Route::post('/ai/temp-chat',[AIChatController::class,'tempChat']);

    //schedule
    Route::get('/schedule',[ScheduleController::class,'index']);
    Route::post('/schedule',[ScheduleController::class,'store']);
    Route::put('/schedule-update',[ScheduleController::class,'update']);
    Route::delete('/schedule-delete',[ScheduleController::class,'destroy']);

    //forum
    Route::post('/forum',[ForumController::class,'store']);
    Route::post('/forum/{id}/comments',[ForumController::class,'storeComment']);
    Route::post('/forum-categories', [ForumController::class, 'storeCategory']);

    //products modeling
    Route::get('/product-categories',[ProductController::class,'categories']);
    Route::get('/product',[ProductController::class,'index']);
    Route::get('/product/{id}',[ProductController::class,'show']);
    Route::post('/product-modeling',[ProductController::class,'productModeling'])->name('productModeling');
    Route::post('/product-fixed-costs',[ProductController::class,'productFixedCost'])->name('productFixedCost');

    //hpp calculator
    Route::post('/count-hpp',[HppController::class,'countHpp']);
    Route::post('/count-recommendation',[HppController::class,'recommendation']);
});
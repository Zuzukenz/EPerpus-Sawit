<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API routes moved to Supabase REST API
// Base URL: https://bqyrhbezlurbbrswaxiw.supabase.co/rest/v1/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

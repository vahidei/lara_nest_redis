<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\NestJsService;

Route::get('/greeting', function (Request $request, NestJsService $nestService) {
    $name = $request->get('name');
    $nestResponse = $nestService->send(['cmd' => 'greeting'], $name);
    return $nestResponse->first();
});

Route::get('/observable', function (NestJsService $nestService) {
    $nestResponse = $nestService->send(['cmd' => 'observable'], ['aaa'=>1213, 'vbvv'=>41241]);
    return $nestResponse->sum();
});
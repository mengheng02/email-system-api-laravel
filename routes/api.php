<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmailTemplateController;
use App\Models\User;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/send', [EmailController::class, 'sendSimpleEmail']);
    Route::post('/send-template', [EmailController::class, 'sendTemplatedEmail']);
    Route::get('/email-status/{id}', [EmailController::class, 'getStatus']);

    Route::apiResource('templates', EmailTemplateController::class);

    Route::post('/login', function (Request $request) {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    });
});

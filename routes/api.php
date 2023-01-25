<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', function () {
        try {
            $user = auth('sanctum')->user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout Successfully',
                'code' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }
    });
});

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

// Notes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('notes', App\Http\Controllers\NoteController::class)->except(['create', 'edit', 'show']);
    Route::get('notes/show/{slug}', [NoteController::class, 'show']);
});

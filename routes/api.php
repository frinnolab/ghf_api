<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileRolesController;
use App\Http\Controllers\ProgrammesController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {

    //Auth Endpoints
    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/login', 'login');
            Route::post('/signup', 'signUp');
            Route::post('/reset', 'resetPassword');
        });
    });
    
    //Profile Management Endpoints
    Route::prefix('profiles')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{profileId}', 'show');

            Route::post('/', 'store')->middleware('auth:sanctum');
            Route::put('/{profileId}', 'update')->middleware('auth:sanctum');
            
            Route::delete('/{profileId}', 'destroy')->middleware('auth:sanctum');

        });
    });


    //ProfileRoles Management Endpoints
    Route::prefix('profile-roles')->group(function () {
        Route::controller(ProfileRolesController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{roleId}', 'show');

            Route::post('/{profileId}', 'store')->middleware('auth:sanctum');
            Route::put('/{profileId}', 'update')->middleware('auth:sanctum');
            
            Route::delete('/{profileId}/{roleId}', 'destroy')->middleware('auth:sanctum');

        });
    });


    //Programmes Management Endpoints
    Route::prefix('programmes')->group(function () {
        Route::controller(ProgrammesController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{programmeId}', 'show');

            Route::post('/', 'store')->middleware('auth:sanctum');
            Route::put('/{programmeId}', 'update')->middleware('auth:sanctum');
            
            Route::delete('/{programmeId}', 'destroy')->middleware('auth:sanctum');

            //Programme Assets Management
        });
    });


    //Projects Management Endpoints
    Route::prefix('projects')->group(function () {
        Route::controller(ProjectController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{projectId}', 'show');
            Route::post('/', 'store')->middleware('auth:sanctum');
            Route::put('/{projectId}', 'update')->middleware('auth:sanctum');
            Route::delete('/{projectId}', 'destroy')->middleware('auth:sanctum');

            //Projects Assets Management
        });
    });


    //Partners Management Endpoints
    Route::prefix('partners')->group(function () {
        Route::controller(PartnerController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{partnerId}', 'show');
            Route::post('/', 'store')->middleware('auth:sanctum');
            Route::put('/{partnerId}', 'update')->middleware('auth:sanctum');
            Route::delete('/{partnerId}', 'destroy')->middleware('auth:sanctum');
        });
    });

    //Blogs
    Route::prefix('blogs')->group(function () {
        Route::controller(BlogController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{blogId}', 'show');
            Route::post('/', 'store')->middleware('auth:sanctum');
            Route::put('/{authorId}/{blogId}', 'update')->middleware('auth:sanctum');
            Route::delete('/{blogId}', 'destroy')->middleware('auth:sanctum');
        });
    });
});
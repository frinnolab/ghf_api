<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ImpactsController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileRolesController;
use App\Http\Controllers\ProgrammesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TeamController;
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
            Route::put('/{blogId}', 'update')->middleware('auth:sanctum');
            Route::delete('/{blogId}', 'destroy')->middleware('auth:sanctum');
        });
    });

    //Teams
    Route::prefix('teams')->group(function () {
        Route::controller(TeamController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{teamId}', 'show');
            Route::post('/', 'store')->middleware('auth:sanctum');
            Route::put('/{teamId}', 'update')->middleware('auth:sanctum');
            Route::delete('/{teamId}', 'destroy')->middleware('auth:sanctum');

            //Team Members
            Route::get('/members/main', 'getMainBoardTeam');
            Route::get('/members/{teamId}', 'getTeamMembers');
            Route::get('/members/{teamId}/{memberId}', 'getTeamMember');
            Route::post('/members', 'addMemberToTeam')->middleware('auth:sanctum');
            Route::put('/members', 'updateMemberToTeam')->middleware('auth:sanctum');
            Route::delete('/members/{teamId}/{memberId}', 'removeMemberToTeam')->middleware('auth:sanctum');
        });
    });


    //Settings
    Route::prefix('settings')->group(function () {
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/companyinfo', 'companyInfoIndex');
            Route::get('/summaryinfo', 'summaryInfoIndex');
            Route::post('/companyinfo', 'companyInfoCreate')->middleware('auth:sanctum');
            Route::put('/companyinfo/{infoId}', 'companyInfoUpdate')->middleware('auth:sanctum');
            // Route::put('/{adminId}/companyassets', 'companyInfoAssetsCreate')->middleware('auth:sanctum');
            // Route::delete('/{blogId}', 'destroy')->middleware('auth:sanctum');
        });
    });

    Route::prefix('settings')->group(function () {
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/companyassets', 'assetsIndex');
            Route::get('/companyassets/{assetId}', 'assetsInfo');
            Route::post('/companyassets', 'assetsInfoCreate');//->middleware('auth:sanctum');
            Route::put('/companyassets/{infoId}', 'assetsInfoUpdate')->middleware('auth:sanctum');
            // Route::put('/{adminId}/companyassets', 'companyInfoAssetsCreate')->middleware('auth:sanctum');
            // Route::delete('/{blogId}', 'destroy')->middleware('auth:sanctum');
        });
    });


    //Impacts
    Route::prefix('impacts')->group(function () {
        Route::controller(ImpactsController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{impactId}', 'show');
            Route::post('/', 'store'); //->middleware('auth:sanctum');
            Route::put('/{impactId}', 'update'); //->middleware('auth:sanctum');
            Route::delete('/{impactId}', 'destroy'); //->middleware('auth:sanctum');


            Route::get('/assets/{impactId}', 'assets_index');
            Route::get('/assets/{assetId}', 'assets_show');
            Route::post('/assets/{impactId}', 'assets_store'); //->middleware('auth:sanctum');
            // Route::put('/assets/{assetId}', 'assets_update');//->middleware('auth:sanctum');
            Route::delete('assets/{assetId}', 'assets_destroy'); //->middleware('auth:sanctum');
        });
    });
});

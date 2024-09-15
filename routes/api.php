<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Con;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});

/* records */
Route::get('records', [Con\RecordController::class, 'index']);
Route::get('records/{area}/{toon}', [Con\RecordController::class, 'show']);
Route::get('records/search/{area}/{toon}', [Con\RecordController::class, 'search']);
Route::post('records', [Con\RecordController::class, 'store']);
Route::put('records', [Con\RecordController::class, 'update']);
Route::get('records/count', [Con\RecordController::class, 'count']);

/* replies */
Route::get('replies/migration', [Con\ReplyController::class, 'migration']);
Route::get('replies/{area}/{toon}', [Con\ReplyController::class, 'index']);
Route::post('replies', [Con\ReplyController::class, 'store']);
Route::post('replies/{id}', [Con\ReplyController::class, 'destroy']);

/* feedbacks */
Route::get('feedbacks', [Con\FeedbackController::class, 'index']);
Route::post('feedbacks', [Con\FeedbackController::class, 'store']);

/* recordOldSeasons */
Route::post('recordOldSeasons', [Con\RecordOldSeasonController::class, 'store']);

/* recordNewSeasons */
Route::post('recordNewSeasons', [Con\RecordNewSeasonController::class, 'store']);
Route::delete('recordNewSeasons', [Con\RecordNewSeasonController::class, 'destroy']);
Route::get('recordNewSeasons/count', [Con\RecordNewSeasonController::class, 'count']);

/* recordTemps */
Route::get('recordTemps/emptyTable', [Con\RecordTempController::class, 'emptyTable']);
Route::get('recordTemps/insertRecordOldSeasons', [Con\RecordTempController::class, 'insertRecordOldSeasons']);
Route::get('recordTemps/insertRecordNewSeasons', [Con\RecordTempController::class, 'insertRecordNewSeasons']);
Route::get('recordTemps/destroyUnusedData', [Con\RecordTempController::class, 'destroyUnusedData']);
Route::get('recordTemps/replaceTable', [Con\RecordTempController::class, 'replaceTable']);
Route::get('recordTemps/downAvatarImage', [Con\RecordTempController::class, 'downAvatarImage']);

/*
Route::get('/statistics', [Con\StatisticsController::class, 'index']);
Route::get('/statistics/ability_kind', [Con\StatisticsController::class, 'ability_kind']);
Route::post('/statistics/numKind', [Con\StatisticsController::class, 'numKind']);
Route::get('/statistics/ability-kind-ajax', [Con\StatisticsController::class, 'ability_kind_ajax']);
*/
Route::get('/statistics/numKind', [Con\StatisticsController::class, 'numKind']);
Route::get('/statistics/ability-kind-ajax', [Con\StatisticsController::class, 'ability_kind_ajax']);



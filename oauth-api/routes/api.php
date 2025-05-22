<?php
declare(strict_types=1);

use Repository\User\UserRepository;
use Illuminate\Support\Facades\Route;
use Repository\Client\ClientRepository;
use App\Http\Middleware\VerifyAccessToken;
use Repository\AuthCode\AuthCodeRepository;
use App\Http\Controllers\Oauth\AuthController;
use App\Http\Controllers\Oauth\UserController;
use App\Http\Controllers\Oauth\ClientController;
use Repository\AccessToken\AccessTokenRepository;
use App\Http\Controllers\Oauth\AuthorizationController;

Route::post(UserRepository::REGISTER_API_ENDPOINT_NAME, [AuthController::class, 'register']);
Route::post(UserRepository::LOGIN_API_ENDPOINT_NAME, [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get(UserRepository::CURRENT_USER_API_ENDPOINT_NAME, [AuthController::class, 'authorizedUserInformation']);
    Route::apiResource(UserRepository::API_ENDPOINT_RESOURCE_NAME, UserController::class);
    Route::apiResource(ClientRepository::API_ENDPOINT_RESOURCE_NAME, ClientController::class);
});

Route::prefix('oauth')->group(function () {
    Route::get(AuthCodeRepository::API_ENDPOINT_RESOURCE_NAME, [AuthorizationController::class, 'authorize']);
    Route::post(AuthCodeRepository::API_ENDPOINT_RESOURCE_NAME, [AuthorizationController::class, 'approveAuthorization']);
    Route::post(AccessTokenRepository::API_ENDPOINT_RESOURCE_NAME, [AuthorizationController::class, 'generateAccessToken']);
    Route::post(AccessTokenRepository::API_ENDPOINT_REFRESH_TOKEN, [AuthorizationController::class, 'refresh']);
    Route::post(AccessTokenRepository::API_ENDPOINT_REVOKE_TOKEN, [AuthorizationController::class, 'revoke']);
});


Route::middleware([VerifyAccessToken::class])->group(function () {
    Route::get(UserRepository::OAUTH_CURRENT_USER_API_ENDPOINT_NAME, [AuthController::class, 'getUserInfo']);
});

Route::get('/translations/{locale}', function ($locale) {
    $path = resource_path("lang/{$locale}.json");
    if (file_exists($path)) {
        return response()->json(json_decode(file_get_contents($path), true));
    }
    return response()->json(['error' => 'Translation file not found.'], 404);
});

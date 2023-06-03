 <?php

 use App\Http\Controllers\Api\Admin\NavigationController;
 use App\Http\Controllers\Api\Admin\ServiceController;
 use App\Http\Controllers\Api\Auth\LoginController;
 use App\Http\Controllers\Api\User\UserController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('/admin/services')->as('admin.services.')->group(function() {
        Route::get('/', [ServiceController::class, 'index'])
            ->name('index')->middleware('ability:service:index');
        Route::post('/', [ServiceController::class, 'store'])
            ->name('store')->middleware('ability:service:create');
        Route::put('/{service}/update-status', [ServiceController::class, 'updateStatus'])
            ->name('updateStatus')->middleware('ability:service:update-status');
    });
    Route::get('admin/routing', [NavigationController::class, 'routing'])->name('admin.routing');


    Route::prefix('/user')->as('user.')->group(function(){
        Route::get('/', [UserController::class, 'show'])->name('show');
        Route::put('/', [UserController::class, 'updateProfile'])->name('updateProfile');
        Route::get('/services', \App\Http\Controllers\Api\User\ServiceController::class)
            ->name('index')->middleware('ability:home');
    });



    Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');
});

Route::post('login', [LoginController::class, 'login'])->name('auth.login');



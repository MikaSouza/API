<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\KbController;
use App\Http\Controllers\InvestController;
use App\Http\Controllers\BilletsController;




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


Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/companies', CompanyController::class);
        Route::resource('/modules', ModuleController::class);

        Route::post('/tickets/attachments', [TicketController::class, 'attachments']);
        Route::post('/tickets/replies', [TicketController::class, 'replies']);
        Route::get('/tickets/attachments/{attachment_id}/{ticket_id}/{name}', [TicketController::class, 'getAttachment']);
        Route::get('/tickets/threads/{ticket_id}/{thread_id}', [TicketController::class, 'threads']);
        Route::get('/tickets/departments', [TicketController::class, 'departments']);
        Route::get('/tickets/classifications', [TicketController::class, 'classifications']);
        Route::get('/tickets/categories/{department_id}', [TicketController::class, 'categories']);
        Route::get('/tickets/subcategories/{department_id}/{category}', [TicketController::class, 'subCategories']);
        Route::resource('/tickets', TicketController::class)->except([
            'update', 'destroy'
        ]);




        Route::get('/kb/articles/categories', [KbController::class, 'categories']);
        Route::get('/kb/articles/categories/{category_id}', [KbController::class, 'listArticlesByCategory']);
        Route::get('/kb/articles/{article_id}', [KbController::class, 'articleDetail']);




        Route::resource('/users', UserController::class);






        //Investimentos
        Route::get('/invest/recommend/wallets', [InvestController::class, 'recommendWallets']);
        Route::get('/invest/recommend/{wallet_id}', [InvestController::class, 'recommend']);

        //Boletos
        Route::get('/billets', [BilletsController::class, 'index']);




        Route::get('/invest/markowitz/products', [InvestController::class, 'markowitzProducts']);
        Route::get('/invest/markowitz/calc/{products}/{application}/{volatility}', [InvestController::class, 'markowitzCalc']);
    });


    Route::prefix('password')->group(function () {
        Route::post('forgot', [PasswordController::class, 'forgotPassword']);
        Route::post('reset', [PasswordController::class, 'resetPassword']);
    });
});

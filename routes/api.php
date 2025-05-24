<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Expense\ExpenseController;
use App\Http\Controllers\Api\Budget\BudgetController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('auth')->as('auth.')->group(function ($route) {

    $route->controller(AuthController::class)
        ->group(function($child) {
            $child->post('login', 'login')->name('login')->middleware(['guest']);
            $child->get('logout', 'logout')->name('logout')->middleware('auth:sanctum');
            $child->post('verify-token', 'verifyToken')->name('verify-token')->middleware('auth:sanctum');
        });
});

Route::prefix('expense')->as('expense.')
    ->controller(ExpenseController::class)
    ->middleware('auth:sanctum')
    ->group(function ($route) {
        $route->get('/', 'index')->name('index');
        $route->post('add', 'create')->name('store');
        $route->get('show/{id}', 'show')->name('show');
        $route->put('update/{id}', 'update')->name('update');
        $route->delete('delete/{id}', 'delete')->name('delete');
    });

Route::prefix('budget')->as('budget.')
    ->controller(BudgetController::class)
    ->middleware('auth:sanctum')
    ->group(function ($route) {
        $route->post('set', 'set')->name('store');
        $route->get('remaining-budget', 'remainingBudget')->name('remainingBudget');
    });

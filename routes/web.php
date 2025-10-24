<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Resource routes for all entities
Route::resource('accounts', AccountController::class);
Route::resource('customers', CustomerController::class);
Route::resource('branches', BranchController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('loans', LoanController::class);
Route::resource('employees', EmployeeController::class);

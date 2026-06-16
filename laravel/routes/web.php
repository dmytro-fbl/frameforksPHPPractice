<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::get('/companies', [CompanyController::class, 'getCompanies']);
Route::get('/companies/{id}', [CompanyController::class, 'getCompanyById']);
Route::post('/companies', [CompanyController::class, 'createCompany']);
Route::patch('/companies/{id}', [CompanyController::class, 'updateCompany']);
Route::delete('/companies/{id}', [CompanyController::class, 'deleteCompany']);

<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signup']);

Route::post('/search', [OperationController::class, 'search']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [UserController::class, 'logout']);

    Route::group(['middleware' => ['is_manager']], function () {
        // manager routes
        Route::post('/add_company', [CompanyController::class, 'addCompany']);
        Route::post('/add_position', [CompanyController::class, 'addPosition']);
        Route::post('/edit_position', [CompanyController::class, 'editPosition']);
        Route::post('/del_position', [CompanyController::class, 'delPosition']);
        Route::post(
            '/get_position_applications/{id}',
            [CompanyController::class, 'positionApplications']
        );
        Route::get('/get_my_company_positions', [CompanyController::class, 'getMyCompanyPositions']);
        Route::get('/get_application_user/{id}', [CompanyController::class, 'applicationUser']);
    });

    Route::group(['middleware' => ['is_admin']], function () {
        // admin routes
    });
    Route::group(['middleware' => ['is_user']], function () {
        // normal user routes
        Route::post('/my-account', [OperationController::class, 'myAccount']);
        Route::post('/update-account', [OperationController::class, 'updateAccount']);
        Route::post('/apply-to-job', [OperationController::class, 'applyJob']);
        Route::post('/load-my-jobs', [OperationController::class, 'loadMyJobs']);
   });
    Route::group(['middleware'=>['logged_in']],function(){
        Route::get('get_positions', [OperationController::class, 'getAllPositions']);
        Route::post('filter', [OperationController::class, 'filter']);
        Route::get('/get_all_companies', [CompanyController::class, 'getAllCompanies']);
        Route::get('/get_company_positions/{id}', [CompanyController::class, 'getCompanyPositions']);
  
    });
});

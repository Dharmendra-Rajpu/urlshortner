<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\DashboardController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');


Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

	Route::get('/generate-url', [DashboardController::class, 'createUrl'])->name('short-urls.create');
	Route::post('/generate-url', [DashboardController::class, 'storeUrl'])->name('short-urls.store');
	

	Route::get('/team-members', [DashboardController::class, 'teamMembers'])->name('team.index');
	Route::get('/invite-team-member', [DashboardController::class, 'inviteteammembers'])->name('team.invite');
	Route::post('/invite-team-member', [DashboardController::class, 'storeInvitemembers'])->name('team.invite.store');




});

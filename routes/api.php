<?php

use App\Http\Controllers\ProfilController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::post('/login', [AuthController::class, 'login']); // Route for admin login

Route::get('/profils-actifs', [ProfilController::class, 'getActiveProfiles']); // Route for getting all active profiles

Route::middleware(["auth:sanctum", EnsureIsAdmin::class])->group(function () {
    Route::post('/admins', [AdminController::class, 'create']); // Route for creating a new admin
    Route::post('/profils', [ProfilController::class, 'store']); // Route for creating a profil
});

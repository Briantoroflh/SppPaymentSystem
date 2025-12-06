<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Student\SppController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/student', [StudentController::class, 'pageLogin'])->name('page.student.login');
Route::post('/login/student', [StudentController::class, 'LoginStudent'])->name('student.login');

Route::middleware('auth:student')->group(function () {
    Route::get('/dashboard/student', [DashboardController::class, 'index'])->name('dashboard.student.index');

    Route::prefix('menu')->group(function () {
        Route::get('', [MenuController::class, 'index'])->name('menu.index');

        Route::get('/all-menu', [MenuController::class, 'getAll'])->name('menu.all');
        Route::post('/store', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/get/{id}', [MenuController::class, 'getById'])->name('menu.get');
        Route::put('/update/{id}', [MenuController::class, 'update'])->name('menu.update');
        Route::delete('/destroy/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
    });

    Route::get('dashboard/student/spp', [SppController::class, 'index'])->name('dashboard.student.spp');

    Route::prefix('student/spp')->group(function () {
        Route::get('/all/{id}', [SppController::class, 'getAllById']);
        Route::get('/{id}', [SppController::class, 'getSppById']);
    });

    Route::prefix('student/payment')->group(function () {
        Route::post('/{studentSppId}', [PaymentController::class, 'store'])->name('student.payment.store');
        Route::get('/success/{paymentId}', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/invoice/{trackingId}/pdf', [PaymentController::class, 'downloadInvoicePdf'])->name('payment.invoice.pdf');
    });
});

// Route untuk Midtrans callback (di luar middleware auth karena redirect dari Midtrans)
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuRoleController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\School\ClassesController;
use App\Http\Controllers\School\SppStudentController;
use App\Http\Controllers\School\StudentClassesController;
use App\Http\Controllers\School\TeacherController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login/admin', [AuthController::class, 'LoginAdmin'])->name('auth.login.admin');
    Route::post('/login/school-admin', [SchoolController::class, 'LoginSchoolAdmin'])->name('auth.login.school.admin');
    Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');
});

Route::middleware(['auth:web', 'check.menu.access'])->group(function () {

    Route::prefix('menu')->group(function () {
        Route::get('', [MenuController::class, 'index'])->name('menu.index');

        Route::get('/all-menu', [MenuController::class, 'getAll'])->name('menu.all');
        Route::post('/store', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/get/{id}', [MenuController::class, 'getById'])->name('menu.get');
        Route::put('/update/{id}', [MenuController::class, 'update'])->name('menu.update');
        Route::delete('/destroy/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
    });

    Route::prefix('dashboard')->group(function () {

        Route::get('', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::prefix('manage-school')->group(function () {
            Route::get('', [SchoolController::class, 'index'])->name('dash.school.index');

            Route::prefix('school')->group(function () {
                Route::get('/all-school', [SchoolController::class, 'getAll'])->name('school.all');
                Route::post('/store', [SchoolController::class, 'store'])->name('school.store');
                Route::get('/get/{id}', [SchoolController::class, 'getById'])->name('school.get');
                Route::put('/update/{id}', [SchoolController::class, 'update'])->name('school.update');
                Route::delete('/destroy/{id}', [SchoolController::class, 'destroy'])->name('school.destroy');
            });
        });

        Route::prefix('manage-major')->group(function () {
            Route::get('', [MajorController::class, 'index'])->name('dash.major.index');

            Route::prefix('major')->group(function () {
                Route::get('/all-major', [MajorController::class, 'getAll'])->name('major.all');
                Route::post('/store', [MajorController::class, 'store'])->name('major.store');
                Route::get('/get/{id}', [MajorController::class, 'getById'])->name('major.get');
                Route::put('/update/{id}', [MajorController::class, 'update'])->name('major.update');
                Route::delete('/destroy/{id}', [MajorController::class, 'destroy'])->name('major.destroy');
            });
        });

        Route::prefix('manage-region')->group(function () {
            Route::get('', [RegionController::class, 'index'])->name('dash.region.index');

            Route::prefix('region')->group(function () {
                Route::get('/all-region', [RegionController::class, 'getAll'])->name('region.all');
                Route::post('/store', [RegionController::class, 'store'])->name('region.store');
                Route::get('/get/{id}', [RegionController::class, 'getById'])->name('region.get');
                Route::put('/update/{id}', [RegionController::class, 'update'])->name('region.update');
                Route::delete('/destroy/{id}', [RegionController::class, 'destroy'])->name('region.destroy');
            });
        });

        Route::prefix('manage-student')->group(function () {
            Route::get('', [StudentController::class, 'index'])->name('dash.student.index');

            Route::prefix('student')->group(function () {
                Route::get('/all-student', [StudentController::class, 'getAll'])->name('student.all');
                Route::post('/store', [StudentController::class, 'store'])->name('student.store');
                Route::get('/get/{id}', [StudentController::class, 'getById'])->name('student.get');
                Route::put('/update/{id}', [StudentController::class, 'update'])->name('student.update');
                Route::delete('/destroy/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
            });
        });

        Route::prefix('manage-users')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('dash.users.index');

            Route::prefix('user')->group(function () {
                Route::get('/all-user', [UserController::class, 'getAll'])->name('user.all');
                Route::post('/store', [UserController::class, 'store'])->name('user.store');
                Route::get('/get/{id}', [UserController::class, 'getById'])->name('user.get');
                Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update');
                Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
            });
        });

        Route::prefix('manage-role')->group(function () {
            Route::get('', [RoleController::class, 'index'])->name('dash.users.index');

            Route::prefix('menu-role')->group(function () {
                Route::get('/{role}', [MenuRoleController::class, 'getMenuByRole'])->name('menuRole');
            });

            Route::prefix('role')->group(function () {
                Route::get('/all-role', [RoleController::class, 'getAll'])->name('role.all');
                Route::post('/store', [RoleController::class, 'store'])->name('role.store');
                Route::get('/get/{id}', [RoleController::class, 'getById'])->name('role.get');
                Route::put('/update/{id}', [RoleController::class, 'update'])->name('role.update');
                Route::delete('/destroy/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
            });
        });

        Route::prefix('manage-spp')->group(function () {
            Route::get('', [SppStudentController::class, 'index'])->name('spp.index');

            Route::prefix('student-spp')->group(function () {
                Route::get('/all-spp', [SppStudentController::class, 'getAll'])->name('studentSpp.all');
                Route::post('/store', [SppStudentController::class, 'store'])->name('studentSpp.store');
                Route::get('/get/{id}', [SppStudentController::class, 'getById'])->name('studentSpp.get');
                Route::put('/update/{id}', [SppStudentController::class, 'update'])->name('studentSpp.update');
                Route::delete('/destroy/{id}', [SppStudentController::class, 'destroy'])->name('studentSpp.destroy');
            });
        });

        Route::prefix('manage-student-classes')->group(function () {
            Route::get('', [StudentClassesController::class, 'index'])->name('student.classes.index');

            Route::prefix('student-class')->group(function () {
                Route::get('/all-class', [StudentClassesController::class, 'getAll'])->name('studentClass.all');
                Route::post('/store', [StudentClassesController::class, 'store'])->name('studentClass.store');
                Route::get('/get/{id}', [StudentClassesController::class, 'getById'])->name('studentClass.get');
                Route::put('/update/{id}', [StudentClassesController::class, 'update'])->name('studentClass.update');
                Route::delete('/destroy/{id}', [StudentClassesController::class, 'destroy'])->name('studentClass.destroy');
            });
        });

        Route::prefix('manage-classes')->group(function () {
            Route::get('', [ClassesController::class, 'index'])->name('classes.index');

            Route::prefix('class')->group(function () {
                Route::get('/all-class', [ClassesController::class, 'getAll'])->name('class.all');
                Route::post('/store', [ClassesController::class, 'store'])->name('class.store');
                Route::get('/get/{id}', [ClassesController::class, 'getById'])->name('class.get');
                Route::put('/update/{id}', [ClassesController::class, 'update'])->name('class.update');
                Route::delete('/destroy/{id}', [ClassesController::class, 'destroy'])->name('class.destroy');
            });
        });

        Route::prefix('manage-teacher')->group(function () {
            Route::get('', [TeacherController::class, 'index'])->name('teacher.index');

            Route::prefix('teacher')->group(function () {
                Route::get('/all-teacher', [TeacherController::class, 'getAll'])->name('teacher.all');
                Route::post('/store', [TeacherController::class, 'store'])->name('teacher.store');
                Route::get('/get/{id}', [TeacherController::class, 'getById'])->name('teacher.get');
                Route::put('/update/{id}', [TeacherController::class, 'update'])->name('teacher.update');
                Route::delete('/destroy/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
            });
        });
    });
});

require __DIR__ . '/student-route.php';

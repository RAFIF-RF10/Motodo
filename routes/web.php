<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\teacher\ReportController;
use App\Http\Controllers\teacher\StudentController;
use App\Http\Controllers\TeacherSubmissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('auth.login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('todolist', TodoListController::class);
        Route::get('settings/users', [UserController::class, 'index'])->name('teacher.settings.users.index');
        Route::get('settings/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('settings/users', [UserController::class, 'store'])->name('users.store');
        Route::get('settings/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('settings/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('settings/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('settings/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
        Route::get('/tasks/{todoListId}/{taskId}/export', [TaskController::class, 'export'])->name('tasks.export');
        Route::get('todolist/{todolist}/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('todolist/{todolist}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('todolist/{todolist}/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('todolist/{todolist}/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::get('todolist/{todolist}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('todolist/{todolist}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('todolist/{todolist}/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::post('/teacher/submission/{id}/set-status', [TeacherSubmissionController::class, 'setStatusFromTask'])->name('teacher.submission.setStatusTask');

        Route::get('/teacher/submissions', [TeacherSubmissionController::class, 'index'])->name('teacher.submission.index');
        Route::put('/teacher/submissions/{id}/status', [TeacherSubmissionController::class, 'updateStatus'])->name('teacher.submission.updateStatus');
    });

    Route::middleware(['role:User'])->group(function () {
        Route::get('todo', [TodoListController::class, 'studentIndex'])->name('student.todo.index');
        Route::get('todo/{todolist}', [TodoListController::class, 'studentShow'])->name('student.todo.show');
        Route::get('task/{task}', [TaskController::class, 'studentShow'])->name('student.task.show');
        Route::get('tasks', [TaskController::class, 'studentIndex'])->name('student.tasks.index');
        Route::get('tasks/{task}', [TaskController::class, 'studentShow'])->name('student.tasks.show');

        // Submission routes
        Route::post('tasks/{task}/submit', [SubmissionController::class, 'store'])->name('student.tasks.submit');
        Route::post('task/{task}/submit', [SubmissionController::class, 'store'])->name('student.submissions.store');
        Route::get('my-submissions', [SubmissionController::class, 'studentIndex'])->name('student.submissions.index');
        Route::get('submission/{submission}', [SubmissionController::class, 'studentShow'])->name('student.submissions.show');

        Route::put('submission/{submission}/update', [SubmissionController::class, 'update'])->name('student.submissions.update');
    });
});

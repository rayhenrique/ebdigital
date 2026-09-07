<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CongregationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Secretaria\ClassController;
use App\Http\Controllers\Secretaria\ReportController;
use App\Http\Controllers\Secretaria\StudentController;
use App\Http\Controllers\Secretaria\TeacherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Digital Asset Links for Android TWA verification
Route::get('/.well-known/assetlinks.json', function () {
    $path = public_path('.well-known/assetlinks.json');
    if (file_exists($path)) {
        return response(file_get_contents($path), 200, [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'max-age=86400, public',
        ]);
    }
    abort(404);
});

// Central Dashboard Redirection by Role
Route::get('/dashboard', function () {
    return redirect(AuthenticatedSessionController::redirectPathForUser(Auth::user()));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulo de Chamada Mobile-First (Professores, Secretários e Admin)
    Route::middleware('role:admin,secretario,professor')->group(function () {
        Route::get('/chamada', [AttendanceController::class, 'index'])->name('chamada.index');
        Route::get('/chamada/{class}', [AttendanceController::class, 'take'])->name('chamada.take');
    });

    // Módulo de Secretaria e Painel Consolidado (Secretários e Admin)
    Route::middleware('role:admin,secretario')->group(function () {
        Route::get('/secretaria/dashboard', function () {
            return view('secretaria.dashboard');
        })->name('secretaria.dashboard');

        // Classes / Turmas
        Route::resource('classes', ClassController::class)->except(['show']);
        Route::patch('classes/{class}/toggle', [ClassController::class, 'toggleActive'])->name('classes.toggle');

        // Alunos
        Route::resource('alunos', StudentController::class)->except(['show']);
        Route::patch('alunos/{aluno}/toggle', [StudentController::class, 'toggleActive'])->name('alunos.toggle');

        // Professores da Congregação
        Route::resource('professores', TeacherController::class)->except(['show']);
        Route::patch('professores/{professore}/toggle', [TeacherController::class, 'toggleActive'])->name('professores.toggle');
        Route::patch('professores/{professore}/reset-password', [TeacherController::class, 'resetPassword'])->name('professores.reset-password');

        // Relatórios da EBD
        Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');
    });

    // Módulo Administrativo Exclusivo (Admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Gestão de Congregações
        Route::resource('congregacoes', CongregationController::class)->except(['show']);
        Route::patch('congregacoes/{congregacao}/toggle', [CongregationController::class, 'toggleActive'])->name('congregacoes.toggle');
        Route::post('congregacoes/switch', [CongregationController::class, 'switchTenant'])->name('congregacoes.switch');

        // Gestão de Usuários
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Auditoria & Governança
        Route::get('auditoria', [AuditLogController::class, 'index'])->name('audit.index');
        Route::post('auditoria/purge', [AuditLogController::class, 'purge'])->name('audit.purge');
    });
});

require __DIR__.'/auth.php';

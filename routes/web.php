<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CongregationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ManualController;
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

// Política de Privacidade Pública (Exigência Google Play Store & LGPD)
Route::view('/politica-de-privacidade', 'privacy-policy')->name('privacy.policy');
Route::view('/privacidade', 'privacy-policy');
Route::view('/privacy-policy', 'privacy-policy');

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

// Central Dashboard (Acessível a todos os usuários autenticados)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Redirecionamento de compatibilidade para a rota unificada do dashboard
Route::get('/secretaria/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware(['auth'])->name('secretaria.dashboard');

Route::middleware('auth')->group(function () {
    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manual de Uso Didático e Interativo (Todos os Usuários)
    Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');

    // Módulo de Chamada e Gestão de Alunos (Professores, Secretários e Admin)
    Route::middleware('role:admin,secretario,professor')->group(function () {
        Route::get('/chamada', [AttendanceController::class, 'index'])->name('chamada.index');
        Route::get('/chamada/{class}', [AttendanceController::class, 'take'])->name('chamada.take');

        // Alunos (Professores gerenciam alunos de suas turmas; Secretários e Admin gerenciam da congregação)
        Route::resource('alunos', StudentController::class)->except(['show']);
        Route::patch('alunos/{aluno}/toggle', [StudentController::class, 'toggleActive'])->name('alunos.toggle');

        // Relatórios da EBD (Professores veem apenas salas vinculadas; Secretários e Admin veem da congregação)
        Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');
    });

    // Módulo de Secretaria (Secretários e Admin)
    Route::middleware('role:admin,secretario')->group(function () {
        // Classes / Turmas
        Route::resource('classes', ClassController::class)->except(['show']);
        Route::patch('classes/{class}/toggle', [ClassController::class, 'toggleActive'])->name('classes.toggle');

        // Professores da Congregação
        Route::resource('professores', TeacherController::class)
            ->parameters(['professores' => 'professor'])
            ->except(['show']);
        Route::patch('professores/{professor}/toggle', [TeacherController::class, 'toggleActive'])->name('professores.toggle');
        Route::patch('professores/{professor}/reset-password', [TeacherController::class, 'resetPassword'])->name('professores.reset-password');
    });

    // Módulo Administrativo Exclusivo (Admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Gestão de Congregações
        Route::resource('congregacoes', CongregationController::class)
            ->parameters(['congregacoes' => 'congregacao'])
            ->except(['show']);
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

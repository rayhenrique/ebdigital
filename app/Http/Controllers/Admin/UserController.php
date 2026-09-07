<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status') === 'active';
            $query->where('is_active', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        AuditService::log(
            'USER_CREATED',
            User::class,
            $user->id,
            null,
            ['name' => $user->name, 'email' => $user->email, 'role' => $user->role->value]
        );

        return redirect()->route('admin.users.index')->with('success', "Usuário {$user->name} cadastrado com sucesso!");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => UserRole::cases(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $before = ['name' => $user->name, 'email' => $user->email, 'role' => $user->role->value, 'is_active' => $user->is_active];

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $user->update($validated);

        AuditService::log(
            'USER_UPDATED',
            User::class,
            $user->id,
            $before,
            ['name' => $user->name, 'email' => $user->email, 'role' => $user->role->value, 'is_active' => $user->is_active]
        );

        return redirect()->route('admin.users.index')->with('success', "Usuário {$user->name} atualizado com sucesso!");
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode desativar seu próprio usuário.');
        }

        $oldStatus = $user->is_active;
        $user->is_active = !$oldStatus;
        $user->save();

        AuditService::log(
            'USER_STATUS_TOGGLED',
            User::class,
            $user->id,
            ['is_active' => $oldStatus],
            ['is_active' => $user->is_active]
        );

        $msg = $user->is_active ? 'ativado' : 'desativado';
        return redirect()->back()->with('success', "Usuário {$user->name} {$msg} com sucesso!");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:6'],
        ]);

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        AuditService::log(
            'USER_PASSWORD_RESET',
            User::class,
            $user->id,
            null,
            ['message' => 'Senha redefinida pelo administrador.']
        );

        return redirect()->back()->with('success', "Senha do usuário {$user->name} foi redefinida com sucesso!");
    }
}

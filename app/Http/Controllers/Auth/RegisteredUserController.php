<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Congregation;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $congregations = Congregation::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('auth.register', [
            'congregations' => $congregations,
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'congregation_id' => (int) $validated['congregation_id'],
            'is_active' => false, // Requer aprovação do Administrador / Pastor
        ]);

        event(new Registered($user));

        AuditService::log(
            'USER_SELF_REGISTERED',
            User::class,
            $user->id,
            null,
            [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->value,
                'congregation_id' => $user->congregation_id,
                'congregation_name' => $user->congregation?->name,
                'is_active' => false,
            ]
        );

        // Guardamos os dados na sessão flash para a tela de confirmação e link do WhatsApp
        $request->session()->flash('registered_user', [
            'name' => $user->name,
            'email' => $user->email,
            'role_label' => $user->role->label(),
            'congregation_name' => $user->congregation?->name ?? 'Não informada',
        ]);

        return redirect()->route('register.success');
    }

    /**
     * Display the registration success view with WhatsApp direct link.
     */
    public function success(Request $request): View
    {
        $registeredUser = $request->session()->get('registered_user');

        $phone = '5582996304742';
        $phoneDisplay = '+55 (82) 99630-4742';

        $name = $registeredUser['name'] ?? 'Novo Usuário';
        $email = $registeredUser['email'] ?? '';
        $roleLabel = $registeredUser['role_label'] ?? 'Secretário / Professor';
        $congregationName = $registeredUser['congregation_name'] ?? 'Congregação';

        $message = "Acabei de fazer o cadastro no app Caderneta EBD Online, com os seguintes dados:\n"
            . "- Nome: {$name}\n"
            . "- E-mail: {$email}\n"
            . "- Perfil: {$roleLabel}\n"
            . "- Congregação: {$congregationName}\n\n"
            . "Gostaria da liberação do meu cadastro.";

        $waLink = 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);

        return view('auth.register-success', [
            'registeredUser' => $registeredUser,
            'waLink' => $waLink,
            'phoneDisplay' => $phoneDisplay,
        ]);
    }
}

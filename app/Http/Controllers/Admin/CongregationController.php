<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCongregationRequest;
use App\Http\Requests\Admin\UpdateCongregationRequest;
use App\Models\Congregation;
use App\Services\AuditService;
use App\Services\TenantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CongregationController extends Controller
{
    public function __construct(
        protected TenantService $tenantService
    ) {}

    public function index(Request $request): View
    {
        $query = Congregation::query()
            ->withCount(['classes', 'students', 'teachers', 'secretarios'])
            ->orderBy('is_headquarters', 'desc')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('pastor_dirigente', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $congregations = $query->paginate(15)->withQueryString();

        return view('admin.congregations.index', [
            'congregations' => $congregations,
        ]);
    }

    public function create(): View
    {
        return view('admin.congregations.create');
    }

    public function store(StoreCongregationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_headquarters'] = $request->boolean('is_headquarters');
        $validated['is_active'] = $request->boolean('is_active', true);

        // Se for marcado como sede, desmarca outras sedes
        if ($validated['is_headquarters']) {
            Congregation::where('is_headquarters', true)->update(['is_headquarters' => false]);
        }

        $congregation = Congregation::create($validated);

        AuditService::log(
            'CONGREGATION_CREATED',
            Congregation::class,
            $congregation->id,
            null,
            ['name' => $congregation->name, 'city' => $congregation->city]
        );

        return redirect()->route('admin.congregacoes.index')
            ->with('success', "Congregação {$congregation->name} cadastrada com sucesso!");
    }

    public function edit(Congregation $congregacao): View
    {
        return view('admin.congregations.edit', [
            'congregation' => $congregacao,
        ]);
    }

    public function update(UpdateCongregationRequest $request, Congregation $congregacao): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_headquarters'] = $request->boolean('is_headquarters');
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($validated['is_headquarters'] && ! $congregacao->is_headquarters) {
            Congregation::where('is_headquarters', true)->update(['is_headquarters' => false]);
        }

        $before = $congregacao->only(['name', 'pastor_dirigente', 'city', 'is_headquarters', 'is_active']);
        $congregacao->update($validated);

        AuditService::log(
            'CONGREGATION_UPDATED',
            Congregation::class,
            $congregacao->id,
            $before,
            $congregacao->only(['name', 'pastor_dirigente', 'city', 'is_headquarters', 'is_active'])
        );

        return redirect()->route('admin.congregacoes.index')
            ->with('success', "Congregação {$congregacao->name} atualizada com sucesso!");
    }

    public function toggleActive(Congregation $congregacao): RedirectResponse
    {
        if ($congregacao->is_headquarters) {
            return redirect()->back()->with('error', 'O Templo Sede não pode ser desativado.');
        }

        $congregacao->is_active = ! $congregacao->is_active;
        $congregacao->save();

        AuditService::log(
            'CONGREGATION_STATUS_TOGGLED',
            Congregation::class,
            $congregacao->id,
            null,
            ['is_active' => $congregacao->is_active]
        );

        $status = $congregacao->is_active ? 'ativada' : 'desativada';
        return redirect()->back()->with('success', "Congregação {$congregacao->name} {$status} com sucesso!");
    }

    public function destroy(Congregation $congregacao): RedirectResponse
    {
        if ($congregacao->is_headquarters) {
            return redirect()->back()->with('error', 'O Templo Sede não pode ser excluído.');
        }

        if ($congregacao->classes()->count() > 0 || $congregacao->students()->count() > 0) {
            return redirect()->back()->with('error', 'Esta congregação possui turmas ou alunos vinculados e não pode ser excluída.');
        }

        $name = $congregacao->name;
        $congregacao->delete();

        AuditService::log(
            'CONGREGATION_DELETED',
            Congregation::class,
            $congregacao->id,
            ['name' => $name],
            null
        );

        return redirect()->route('admin.congregacoes.index')
            ->with('success', "Congregação {$name} removida com sucesso!");
    }

    /**
     * Alterna a congregação ativa no contexto da sessão do Administrador.
     */
    public function switchTenant(Request $request): RedirectResponse
    {
        $target = $request->input('congregation_id');

        if ($target === 'all' || empty($target)) {
            $this->tenantService->setCongregationId(null);
            return redirect()->back()->with('success', 'Visualizando todas as congregações do campo.');
        }

        $congregation = Congregation::where('is_active', true)->findOrFail((int) $target);
        $this->tenantService->setCongregationId($congregation->id);

        return redirect()->back()->with('success', "Visualizando congregação: {$congregation->name}");
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\EbdClass;
use App\Models\LessonRecord;
use App\Services\TenantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        protected TenantService $tenantService
    ) {}

    /**
     * Display list of classes available for the current user to take attendance.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $date = $request->input('date', now()->format('Y-m-d'));

        if ($user->isProfessor()) {
            $classes = $user->teachingClasses()->where('is_active', true)->withCount('activeStudents')->get();
        } else {
            $classes = EbdClass::active()->with(['teachers', 'congregation'])->withCount('activeStudents')->orderBy('name')->get();
        }

        // Get lesson records for selected date
        $lessonRecords = LessonRecord::whereIn('class_id', $classes->pluck('id'))
            ->where('lesson_date', $date)
            ->get()
            ->keyBy('class_id');

        return view('chamada.index', [
            'turmas' => $classes,
            'classes' => $classes,
            'lessonRecords' => $lessonRecords,
            'selectedDate' => $date,
            'isAllCongregations' => $this->tenantService->isAllCongregations(),
        ]);
    }

    /**
     * Show attendance taking interface for a specific class.
     */
    public function take(EbdClass $class, Request $request): View|RedirectResponse
    {
        if ($this->tenantService->isAllCongregations()) {
            return redirect()->route('chamada.index')
                ->with('warning', 'Selecione uma congregação no menu para poder lançar chamadas.');
        }

        $user = Auth::user();

        if ($user->isProfessor()) {
            $teaches = $user->teachingClasses()->where('classes.id', $class->id)->exists();
            if (!$teaches) {
                abort(403, 'Você não leciona nesta classe.');
            }
        } elseif ($user->isSecretario()) {
            $userCongregation = $user->congregation_id ?? 1;
            if ($class->congregation_id !== $userCongregation) {
                abort(403, 'Você não possui permissão para acessar a chamada de outra congregação.');
            }
        }

        $date = $request->input('date', now()->format('Y-m-d'));

        return view('chamada.take', [
            'class' => $class,
            'date' => $date,
        ]);
    }
}

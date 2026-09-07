<?php

declare(strict_types=1);

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Exibir o painel de relatórios analíticos da EBD.
     */
    public function index(Request $request): View
    {
        return view('secretaria.reports.index');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ManualController extends Controller
{
    /**
     * Exibir o Manual de Uso Didático e Interativo do Sistema.
     */
    public function index(Request $request): View
    {
        return view('manual.index');
    }
}

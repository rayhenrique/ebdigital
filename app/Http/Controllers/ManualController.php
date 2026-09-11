<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ManualController extends Controller
{
    /**
     * Exibir o Manual de Uso Didático e Interativo do Sistema.
     */
    public function index(Request $request): View
    {
        return view('manual.index');
    }

    /**
     * Download do Manual de Uso Oficial em formato PDF.
     */
    public function download(Request $request): BinaryFileResponse
    {
        $filePath = public_path('docs/manual.pdf');

        if (! file_exists($filePath)) {
            $filePath = public_path('manual.pdf');
        }

        abort_unless(file_exists($filePath), 404, 'Arquivo do manual PDF não encontrado.');

        return response()->download($filePath, 'Manual_EBD_Digital.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Rotina programada de expurgo de logs com mais de 90 dias (RF14)
Schedule::command('audit:prune --days=90')->daily()->at('03:00');

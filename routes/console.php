<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Vérification quotidienne des abonnements (expiration, essais, rappels)
Schedule::command('abonnements:verifier')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/abonnements-verifier.log'));

// Génération des factures d'abonnement le 1er de chaque mois à 02:00
Schedule::command('abonnements:facturer')
    ->monthlyOn(1, '02:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/abonnements-facturer.log'));

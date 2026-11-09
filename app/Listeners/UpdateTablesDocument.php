<?php

namespace App\Listeners;

use App\Database\TablesDocument;
use Illuminate\Database\Events\MigrationsEnded;

class UpdateTablesDocument
{
    public function handle(MigrationsEnded $event): void
    {
        if (app()->runningUnitTests() || ($event->options['pretend'] ?? false)) {
            return;
        }

        app(TablesDocument::class)->write();
    }
}

<?php

namespace App\Console\Commands;

use App\Database\TablesDocument;
use Illuminate\Console\Command;

class WriteTablesDocument extends Command
{
    protected $signature = 'tables:document {--path=}';

    protected $description = 'Write the current database tables and columns to the tables document';

    public function handle(TablesDocument $document): int
    {
        $path = $this->option('path');

        $written = $document->write(is_string($path) && $path !== '' ? $path : null);

        $this->components->info('Updated '.$written);

        return self::SUCCESS;
    }
}

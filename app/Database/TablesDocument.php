<?php

namespace App\Database;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TablesDocument
{
    public function __construct(private ?Connection $connection = null) {}

    private function connection(): Connection
    {
        return $this->connection ?? DB::connection();
    }

    public function markdown(): string
    {
        $schema = $this->connection()->getSchemaBuilder();
        $database = $this->connection()->getDatabaseName();

        $lines = [
            '# Tables',
            '',
            'Database: `'.$database.'`',
            '',
            'This file is rewritten from the database after each migration.',
            '',
        ];

        foreach ($this->tables() as $table) {
            $lines[] = '## '.$table;
            $lines[] = '';

            foreach ($schema->getColumns($table) as $column) {
                $lines[] = '- '.$column['name'];
            }

            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    public function write(?string $path = null): string
    {
        $path ??= base_path('docs/pos-database.md');

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->markdown());

        return $path;
    }

    /**
     * @return list<string>
     */
    private function tables(): array
    {
        $database = $this->connection()->getDatabaseName();

        return collect($this->connection()->getSchemaBuilder()->getTables())
            ->filter(fn (array $table): bool => ! in_array($table['name'], self::excludedTables(), true) && $this->belongsToConnection($table, $database))
            ->pluck('name')
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    private static function excludedTables(): array
    {
        return [
            'cache',
            'cache_locks',
            'failed_jobs',
            'job_batches',
            'jobs',
            'migrations',
            'password_reset_tokens',
            'sessions',
        ];
    }

    /**
     * @param  array<string, mixed>  $table
     */
    private function belongsToConnection(array $table, string $database): bool
    {
        $schema = $table['schema'] ?? null;

        return $schema === null || $schema === 'main' || $schema === $database;
    }
}

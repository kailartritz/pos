<?php

use App\Database\TablesDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

test('the tables document lists each table and its columns', function () {
    $path = storage_path('framework/testing/tables.md');

    app(TablesDocument::class)->write($path);

    $document = File::get($path);

    expect($document)
        ->toContain('## users')
        ->toContain('- first_name')
        ->toContain('- last_name')
        ->toContain('- date_of_birth')
        ->toContain('- state')
        ->not->toContain('## sessions')
        ->not->toContain('## cache')
        ->not->toContain('## migrations');

    File::delete($path);
});

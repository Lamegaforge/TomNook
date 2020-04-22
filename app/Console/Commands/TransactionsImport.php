<?php

namespace App\Console\Commands;

use App\Transaction;
use Illuminate\Console\Command;
use App\Services\SheetsDeduplicator;
use App\Managers\Sheets\SheetsManager;

class TransactionsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');

        $sheets = app(SheetsManager::class)->import($path);

        $sheets = app(SheetsDeduplicator::class)->sanitize($sheets);

        Transaction::insert($sheets->toArray());

        $this->info($sheets->count() . ' transaction(s) insérée(s).');
    }
}

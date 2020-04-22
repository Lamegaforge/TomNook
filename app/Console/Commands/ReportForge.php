<?php

namespace App\Console\Commands;

use View;
use Datetime;
use App\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ReportForge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:forge {date?}';

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
        $date = $this->argument('date') ?? with(new DateTime('last month'))->format('Y-m');

        $transactions = Transaction::where('paid_at', 'LIKE', $date . '%')->get();

        $intputTransactions = $this->getIntputTransactions($transactions);
        $outputTransactions = $this->getOutputTransactions($transactions);

        $parameters = [
            'input_amount' => $intputTransactions->sum('amount'),
            'output_amount' => $outputTransactions->sum('amount'),
            'input_transactions_history' => $this->transformHistory($intputTransactions)->sortDesc(),
            'output_transactions_history' => $this->transformHistory($outputTransactions)->sort(),
        ];

        View::make('report', $parameters)->toHtml();
    }

    protected function getIntputTransactions(Collection $transactions): Collection
    {
        return $transactions->where('amount', '>', 0);
    }

    protected function getOutputTransactions(Collection $transactions): Collection
    {
        return $transactions->where('amount', '<', 0);
    }

    protected function transformHistory(Collection $transactions): Collection
    {
        $grouped = $transactions->groupBy('name');

        return $grouped->map(function ($transaction) {
            return $transaction->sum('amount');
        });
    }
}

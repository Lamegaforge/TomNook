<?php

namespace App\Services;

use App\Transaction;
use Illuminate\Support\Collection;

class SheetsDeduplicator
{
    public function sanitize(Collection $sheets): Collection
    {
        $transactions = Transaction::select('tracking')->pluck('tracking');

        return $sheets->filter(function ($sheet) use ($transactions) {
            return ! $transactions->contains($sheet['tracking']);
        });
    }
}

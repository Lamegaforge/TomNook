<?php

namespace App\Managers\Sheets\Drivers;

use DateTime;
use Generator;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Collection;
use App\Managers\Sheets\Contracts\Driver;

class League implements Driver
{
    public function import(string $path): Collection
    {
        $csv = Reader::createFromPath($path, 'r')->setHeaderOffset(0);

        $records = with(new Statement())->process($csv)->getRecords();

        return $this->transform($records);
    }

    protected function transform(Generator $records): Collection
    {
        return with(new Collection($records))->filter(function ($record) {
                return $record['Type'] != 'Conversion de devise standard';
            })
            ->map(function ($record) {
                return [
                    'tracking' => $record['Numéro de transaction'], // vive la France
                    'name' => $record['Nom'] ?? null,
                    'from' => $record["De l'adresse email"] ?? null,
                    'to' => $record["À l'adresse email"] ?? null,
                    'amount' => (int) $record['Net'],
                    'paid_at' => $this->getPaidAt($record),
                ];
            })
            ->values();
    }

    protected function getPaidAt(array $record): string
    {
        $paidAt = DateTime::createFromFormat('d/m/YH:i:s', $record['Date'] . $record['Heure']);

        return $paidAt->format('Y-m-d');
    }
}

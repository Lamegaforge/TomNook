<?php

namespace App\Managers\Sheets;

use Illuminate\Support\Collection;
use App\Managers\Sheets\Contracts\Driver;

class Repository
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function import(string $path): Collection
    {
        return $this->driver->import($path);
    }
}

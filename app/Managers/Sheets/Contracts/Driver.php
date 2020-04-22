<?php

namespace App\Managers\Sheets\Contracts;

use Illuminate\Support\Collection;

interface Driver
{
    public function import(string $path): Collection;
}

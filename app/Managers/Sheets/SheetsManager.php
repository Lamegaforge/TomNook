<?php

namespace App\Managers\Sheets;

use DeGraciaMathieu\Manager\Manager;
use App\Managers\Sheets\Drivers\League;
use App\Managers\Sheets\Contracts\Driver;

class SheetsManager extends Manager 
{
    public function getDefaultDriver(): string
    {
        return 'league';
    }
    
    public function createLeagueDriver(): Repository
    {
        $driver = new League();

        return $this->repository($driver);
    }

    protected function repository(Driver $driver): Repository
    {
        return new Repository($driver);
    }
}

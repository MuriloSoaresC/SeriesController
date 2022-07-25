<?php 

namespace App\Http\Controllers\Api;

use App\Models\Series;

Class SeasonsController
{
    public function index(Series $series)
    {
        return $series->seasons;
    }
}
<?php 

namespace App\Http\Controllers\Api;

use App\Models\Episode;
use App\Models\Series;
use Illuminate\Http\Request;

Class EpisodesController
{
    public function index(Series $series)
    {
        return $series->episodes;
    }

    public function update(Episode $episode, Request $request)
    {
        $episode->watched = $request->watched;
        $episode->save();

        return $episode;
    }
}
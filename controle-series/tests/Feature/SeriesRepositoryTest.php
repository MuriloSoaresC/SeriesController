<?php

namespace Tests\Feature;

use App\Http\Requests\SeriesFormRequest;
use App\Repositories\SeriesRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeriesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function teste_when_a_series_is_created_its_seasons_and_episodes_must_also_be_created()
    {
      /** @var SeriesRepository $repository */
      $repository = $this->app->make(SeriesRepository::class);
      $request = new SeriesFormRequest();
      $request->nome = 'Nome da série';
      $request->seasonsQty = 1;
      $request->episodesPerSeason = 1;

      $repository->add($request);

      $this->assertDatabaseHas('series', ['nome' => 'Nome da Série']);
      $this->assertDatabaseHas('seasons', ['number' => '1']);
      $this->assertDatabaseHas('episodes', ['number' => '1']);
    }
}

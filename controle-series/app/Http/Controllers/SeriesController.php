<?php

namespace App\Http\Controllers;

use App\Events\SeriesCreated as EventsSeriesCreated;
use App\Http\Middleware\Autenticador;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Series;
use App\Repositories\EloquentSeriesRepository;
use App\Repositories\SeriesRepository;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
public function __construct(private SeriesRepository $repository)
{
   $this->middleware(Autenticador::class)->except('index'); 
}
    public function index(Request $request)
    {
        $series = Series::all();
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');


        return view('series.index')->with('series', $series)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request, EloquentSeriesRepository $repository)
    {
       $coverPath =  $request->hasFile('cover') ? $request->hfile('cover')->store('series_cover', 'public') : null;
       $request->coverPath = $coverPath;
        $serie = $repository->add($request);
        $seriesCreatedEvent = new EventsSeriesCreated(
            $serie->nome,
            $serie->id,
            $request->seasonsQty,
            $request->episodesPerSeason,
        );
        event($seriesCreatedEvent);
        
        return to_route('series.index')->with('mensagem.sucesso', "Série {$serie->nome} adicionada com sucesso");
    }

    public function destroy(Series $series)
    {
        $series->delete();

        return to_route('series.index')->with('mensagem.sucesso', "Série {$series->nome} removida com sucesso");
    }

    public function edit(Series $series)
    {
        return view('series.edit')->with('serie', $series);
    }

    public function update(Series $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();

        return to_route('series.index')->with('mensagem.sucesso', "Série '{$series->nome}' atuzalizada com sucesso");
    }
}

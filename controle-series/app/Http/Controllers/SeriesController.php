<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Autenticador;
use App\Http\Requests\SeriesFormRequest;
use App\Mail\SeriesCreated;
use App\Models\Series;
use App\Models\User;
use App\Repositories\EloquentSeriesRepository;
use App\Repositories\SeriesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    $serie = $repository->add($request);

    $userList = User::all();
    foreach ($userList as $user) {
        $email = new SeriesCreated(
            $serie->nome,
            $serie->id,
            $request->seasonsQty,
            $request->episodesPerSeason,
        );
        Mail::to($user)->send($email);
        sleep(2);
    }
        
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

<x-layout title="Editar Série '{!! $serie->nome !!}'">

    <form action="{{ route('series.update', $serie->id) }}" method="post">
        @csrf
        <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome') }}">
        </div>
        @method('PUT')
        <button type="submit" class="btn btn-primary">Editar</button>
    </form>

</x-layout>
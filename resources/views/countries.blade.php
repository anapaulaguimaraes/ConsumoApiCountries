<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Países do Mundo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">

        @if(request('search'))
            <a href="{{ url('/') }}" class="btn-inicio">&#9664; Início</a>
        @else
            <h1>Países do Mundo</h1>
        @endif

        <form method="GET" action="{{ url('/') }}" class="search">
            <input type="text" name="search" placeholder="Informe o nome do país" value="{{ request('search') }}">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <div class="countries">
        @forelse ($countries as $country)
        <div class="country-info">
            <img src="{{ $country['flags']['png'] }}" alt="Bandeira de {{ $country['name']['common'] }}" class="flag">
            <h2>{{ $country['name']['common'] }}</h2>
            <p>Capital: {{ $country['capital'][0] ?? 'Sem capital' }}</p>
            <p>População: {{ $country['formatted_population'] }}</p>
        </div>
        @empty
        <p>Nenhum país encontrado.</p>
        @endforelse
    </div>

    <div class="pagination">
        <div class="pagination">
            @if ($currentPage > 1)
            <a href="{{ url('?page=' . ($currentPage - 1) . '&search=' . request('search')) }}" title="Anterior">
                &#9664; Anterior
            </a>
            @endif

            @if ($currentPage < ceil($total / $perPage))
                <a href="{{ url('?page=' . ($currentPage + 1) . '&search=' . request('search')) }}" title="Proximo">
                Próximo &#9654;
                </a>
            @endif
        </div>
    </div>

</body>

</html>
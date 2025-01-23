<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Filme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .detalhes-filme {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .detalhes-filme img {
            width: 100%;
            border-radius: 8px;
        }
        .info-filme {
            margin-top: 20px;
        }
        .titulo-filme {
            font-size: 2em;
            font-weight: bold;
        }
        .resumo-filme {
            font-size: 1em;
            margin-top: 10px;
        }
        .metadados-filme {
            margin-top: 20px;
        }
        .botao-voltar {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
        }
        .botao-voltar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="/tmdb" class="botao-voltar">Voltar para a lista de filmes</a>

    <div class="detalhes-filme">
        <img src="https://image.tmdb.org/t/p/w500{{ $data['poster_path'] }}" alt="{{ $data['title'] }}">
        <div class="info-filme">
            <div class="titulo-filme">{{ $data['title'] }}</div>
            <div class="resumo-filme">{{ $data['overview'] }}</div>
            <div class="metadados-filme">
                <p><strong>Data de Lançamento:</strong> {{ \Carbon\Carbon::parse($data['release_date'])->format('d/m/Y') }}</p>
                <p><strong>Avaliação:</strong> 🌟 {{ $data['vote_average'] }}</p>
                <p><strong>Gêneros:</strong> {{ implode(', ', array_column($data['genres'], 'name')) }}</p>
            </div>
        </div>
    </div>
</body>
</html>

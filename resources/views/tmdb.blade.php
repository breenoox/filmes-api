<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Filmes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="container text-center mt-5">
        <h1 class="h1">Filmes</h1>
        <div class="row justify-content-center mb-4">
            <div class="col-auto d-flex align-items-center mb-2">
                <p class="mb-0 me-2 d-inline text-nowrap">Escolha um gênero: </p>
                <select class="form-select w-100" id="selectGenero"></select>
            </div>


            <form id="formBusca" onsubmit="buscarFilmesPorPalavra(event)" class="d-flex align-items-center justify-content-center w-100">
                <input type="text" class="form-control me-2 w-100" id="inputBusca" placeholder="Procurar filme..." />
                <button class="btn btn-primary" type="submit">Buscar</button>
                <a class="btn btn-secondary ms-2" type="submit">Limpar</a>
            </form>
        </div>

        <div id="filmes" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4"></div>

        <div class="d-flex justify-content-center mt-4 mb-4">
            <button id="paginaAnterior" class="btn btn-secondary" disabled>Anterior</button>
            <button id="proximaPagina" class="btn btn-secondary ms-2">Próximo</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/tmdb/generos/retornaGeneros',
                type: 'GET', 
                success: function(data) {
                    let select = $('#selectGenero');
                    select.empty();
                    select.append('<option value="">Selecione um gênero</option>');

                    data.genres.forEach(function(genero) {
                        select.append('<option value="' + genero.id + '">' + genero.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao buscar gêneros:', error);
                }
            });
        });

        const URL_API = '/tmdb';
        let paginaAtual = 1;

        async function buscarFilmes(pagina = 1, palavra = '') {
            try {
                const url = palavra ?
                    `/tmdb/${palavra}?page=${pagina}` :
                    `/api/tmdb?page=${pagina}`;

                const resposta = await fetch(url);
                const dados = await resposta.json();

                if (palavra && dados && dados.original && dados.original.results) {
                    if (Array.isArray(dados.original.results) && dados.original.results.length > 0) {
                        exibirFilmes(dados.original.results);
                        atualizarPaginacao(dados.original.page, dados.original.total_pages);
                    } else {
                        document.getElementById('filmes').innerHTML = '<p>Nenhum filme encontrado.</p>';
                    }
                } else if (dados && dados.results) {
                    if (Array.isArray(dados.results) && dados.results.length > 0) {
                        exibirFilmes(dados.results);
                        atualizarPaginacao(dados.page, dados.total_pages);
                    } else {
                        document.getElementById('filmes').innerHTML = '<p>Nenhum filme encontrado.</p>';
                    }
                } else {
                    document.getElementById('filmes').innerHTML = '<p>Erro ao buscar filmes.</p>';
                }
            } catch (erro) {
                document.getElementById('filmes').innerHTML = '<p>Erro ao buscar filmes.</p>';
            }
        }

        function exibirFilmes(filmes) {
            const containerFilmes = document.getElementById('filmes');
            containerFilmes.innerHTML = '';

            filmes.forEach(filme => {
                const cartaoFilme = document.createElement('div');
                cartaoFilme.className = 'col';

                cartaoFilme.innerHTML = `
                    <div class="card h-100">
                        <img src="https://image.tmdb.org/t/p/w500${filme.poster_path}" class="card-img-top" alt="${filme.title}">
                        <div class="card-body">
                            <h5 class="card-title text-center">${filme.title}</h5>
                            <p class="card-text text-truncate">${filme.overview}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-warning text-dark"><strong>🌟 ${filme.vote_average}</strong></span>
                                <span class="text-muted"><strong>📅 ${new Date(filme.release_date).toLocaleDateString('pt-BR')}</strong></span>
                            </div>
                        </div>
                    </div>
                `;

                cartaoFilme.onclick = () => {
                    window.location.href = `/tmdb/detalhes/${filme.id}`;
                };

                containerFilmes.appendChild(cartaoFilme);
            });
        }

        function buscarFilmesPorPalavra(event) {
            event.preventDefault();
            const palavra = document.getElementById('inputBusca').value.trim();
            buscarFilmes(1, palavra);
        }

        function atualizarPaginacao(atual, total) {
            const botaoAnterior = document.getElementById('paginaAnterior');
            const botaoProxima = document.getElementById('proximaPagina');

            botaoAnterior.disabled = atual === 1;
            botaoProxima.disabled = atual === total;

            botaoAnterior.onclick = () => {
                if (atual > 1) {
                    paginaAtual -= 1;
                    buscarFilmes(paginaAtual);
                }
            };

            botaoProxima.onclick = () => {
                if (atual < total) {
                    paginaAtual += 1;
                    buscarFilmes(paginaAtual);
                }
            };
        }

        buscarFilmes(paginaAtual);
    </script>
</body>

</html>
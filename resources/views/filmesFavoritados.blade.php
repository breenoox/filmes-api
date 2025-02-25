<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Filmes Favoritos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container text-center mt-5">
        <h1 class="h1">Filmes Favoritos</h1>
        
        <div id="filmes" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4"></div>

        <div class="d-flex justify-content-center mt-4 mb-4">
            <button id="paginaAnterior" class="btn btn-secondary" disabled>Anterior</button>
            <button id="proximaPagina" class="btn btn-secondary ms-2">Próximo</button>
        </div>

        <a href="/" class="btn btn-primary position-fixed top-0 end-0 m-3">
            Lista de Filmes
        </a>
    </div>

    <div class="modal fade" id="modalDetalhesFilme" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTituloFilme"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="row" style="height: 100%;">

                    <div class="col-md-4">
                        <img id="modalImagemFilme" src="" alt="" class="img-fluid">
                    </div>

                    <div class="col-md-8 d-flex flex-column">
                        <div class="resumo-filme" id="modalResumoFilme"></div>
                        <div class="informacoes-filme  mt-5" id="modalInformacoesFilme"></div>
                        <button class="btn btn-primary ms-2" id="btnDesfavoritarFilme">Desfavoritar Filme</button>
                    </div>

                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        retornarFilmesFavoritos();
        retornarGenerosSelect();


        $('#btnDesfavoritarFilme').on('click', function(e){
            e.preventDefault();

            if(confirm('Deseja realmente desfavoritar esse filme?')) {
                const idFilme = $('#btnDesfavoritarFilme').val();
                desfavoritarFilme(idFilme);
            } 
       
        })
    });

    function retornarFilmesFavoritos() {

        $.ajax({
            url: '/retorna-filmes-favoritos', 
            type: 'GET', 
            success: function(dados) {
                console.log(dados)
                if (dados && dados.results) {
                    if (dados.results.length > 0) {
                        exibirFilmes(dados.results); 
                        atualizarPaginacao(dados.page, dados.total_pages); 
                    } else {
                        $('#filmes').html('<p>Nenhum filme encontrado.</p>');
                    }
                } else {
                    $('#filmes').html('<p>Erro ao buscar filmes.</p>');
                }
            },
            error: function() {
                $('#filmes').html('<p>Erro ao buscar filmes.</p>');
            }
        });
    }

    function exibirFilmes(filmes) {
            const containerFilmes = $('#filmes');
            containerFilmes.empty();
        
            filmes.forEach(filme => {
                const cartaoFilme = $(`
                    <div class="col">
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
                    </div>
                `);
            
                cartaoFilme.css('cursor', 'pointer');
                cartaoFilme.click(() => {
                    console.log(filme)
                    $('#modalTituloFilme').text(filme.title);
                    $('#modalImagemFilme').attr('src', `https://image.tmdb.org/t/p/w500${filme.poster_path}`);
                    $('#modalResumoFilme').text(filme.overview);
                    $('#btnDesfavoritarFilme').val(filme.id);

                    const generos = filme.genre_ids.map(id => {
                        const genero = generosDisponiveis.find(g => g.id === id);
                        return genero ? genero.genero : 'Desconhecido';
                    }).join(', ');

                    $('#modalInformacoesFilme').html(`
                        <p><strong>Data de Lançamento:</strong> ${new Date(filme.release_date).toLocaleDateString('pt-BR')}</p>
                        <p><strong>Avaliação:</strong> 🌟 ${filme.vote_average}</p>
                        <p><strong>Gêneros:</strong> ${generos}</p>
                    `);

                    const modal = new bootstrap.Modal(document.getElementById('modalDetalhesFilme'));
                    modal.show();
                });
            
                containerFilmes.append(cartaoFilme);
            });
        }

        function atualizarPaginacao(atual, total) {
            const botaoAnterior = document.getElementById('paginaAnterior');
            const botaoProxima = document.getElementById('proximaPagina');

            botaoAnterior.disabled = atual === 1;
            botaoProxima.disabled = atual === total;

            botaoAnterior.onclick = () => {
                if (atual > 1) {
                    paginaAtual -= 1;
                    filtrarFilmes(); 
                }
            };
        
            botaoProxima.onclick = () => {
                if (atual < total) {
                    paginaAtual += 1;
                    filtrarFilmes(); 
                }
            };
        }

        let generosDisponiveis = [];
        function retornarGenerosSelect()
        {
            $.ajax({
                url: '/tmdb/generos/retornaGeneros',
                type: 'GET', 
                success: function(data) {
                    generosDisponiveis = data;
                }
            });
        }

        function desfavoritarFilme(idFilme)
        {
            $.ajax({
                url: '/desfavoritar-filme',
                type: 'POST',
                data: {
                    idFilme: idFilme
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                success: function(data) {
                    if(data.success === true) {
                        alert('Filme desfavoritado com sucesso.');
                        retornarFilmesFavoritos();
                        
                        $('#modalDetalhesFilme').modal('hide');
                        
                    } else {
                        alert('Não foi possivel desfavoritar o filme.')
                    }
                }
            });
        }
    </script>
</body>
</html>
@extends('layout')
@section('container-class', 'container-fluid')
@section('content')

<div class="col-lg-9 col-xl-9 mx-auto py-md-4">
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-3 border-bottom">
        <span class="fs-4">Documentação do projeto</span>
        <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_backlog">Backlog</button>
            <a href="https://github.com/supremonet2/desafio-livros-php" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-github me-1" aria-hidden="true"></i> Repositório no GitHub
            </a>
            <a href="{{ url('/livros') }}" class="btn btn-outline-primary btn-sm">Abrir painel</a>
        </div>
    </header>

    <main>
        <div class="">
            <p class="lead col-lg-10 mb-4">
                Aplicação web para cadastrar livros, autores e assuntos, com relações muitos-para-muitos
                e um relatório agrupado por autor a partir de uma view do banco de dados.
            </p>
        </div>

        <section id="como-usar" class=" border-bottom" aria-labelledby="titulo-como-usar">
            <h2 id="titulo-como-usar" class="h3 mb-3">Como usar</h2>
            <ol class="list-group list-group-numbered">
                <li class="list-group-item">Na aba <strong>Cadastros</strong>, registre primeiro os autores e assuntos com seus códigos.</li>
                <li class="list-group-item">Na aba <strong>Livros</strong>, informe os dados do livro e selecione um ou mais autores e assuntos.</li>
                <li class="list-group-item">Use os controles da lista para editar ou excluir um cadastro.</li>
                <li class="list-group-item">Na aba <strong>Relatório</strong>, filtre pelo nome do autor, se desejar, e clique em <strong>Buscar</strong>. Quando houver resultados, use o ícone <strong>PDF</strong> para baixar o mesmo relatório.</li>
            </ol>
        </section>

        <section id="banco" class="py-5 " aria-labelledby="titulo-banco">
            <h2 id="titulo-banco" class="h3 mb-3">Modelo de dados</h2>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Objeto</th>
                            <th scope="col">Papel no projeto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><code>livros</code></th>
                            <td>Código, título, editora, edição, ano de publicação e valor em reais.</td>
                        </tr>
                        <tr>
                            <th scope="row"><code>autors</code></th>
                            <td>Código e nome do autor.</td>
                        </tr>
                        <tr>
                            <th scope="row"><code>assuntos</code></th>
                            <td>Código e descrição do assunto.</td>
                        </tr>
                        <tr>
                            <th scope="row"><code>Livro_Autor</code></th>
                            <td>Relaciona livros e autores por duas chaves estrangeiras.</td>
                        </tr>
                        <tr>
                            <th scope="row"><code>Livro_Assunto</code></th>
                            <td>Relaciona livros e assuntos por duas chaves estrangeiras.</td>
                        </tr>
                        <tr>
                            <th scope="row"><code>vw_relatorio_livros_por_autor</code></th>
                            <td>View usada como origem dos dados do relatório.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="relatorio" class="py-5 border-bottom" aria-labelledby="titulo-relatorio">
            <h2 id="titulo-relatorio" class="h3 mb-3">Relatório por autor</h2>
            <p>A busca na tela e o PDF consultam a view <code>vw_relatorio_livros_por_autor</code>. O servidor aplica o filtro de autor e agrupa os livros por autor, incluindo um livro no grupo de cada autor vinculado a ele. Os assuntos de cada livro são reunidos na mesma linha.</p>
            <p class="mb-0">O botão de PDF só aparece após uma busca com resultados. Ao clicar, a aplicação consulta novamente a view com o mesmo filtro e gera o arquivo com DomPDF; não usa uma cópia dos dados da tabela HTML.</p>
        </section>


        <section id="instalacao" class="" aria-labelledby="titulo-instalacao">
            <h2 id="titulo-instalacao" class="h3 mb-3">Instalação local</h2>
            <p>Com PHP 8.3 ou superior, Composer e MySQL disponíveis, instale as dependências, incluindo <code>barryvdh/laravel-dompdf</code>, e prepare o ambiente na raiz do projeto:</p>
            <pre class="bg-body-tertiary border rounded-3 p-3 overflow-auto"><code>composer install
cp -n .env.example .env</code></pre>
            <p>Se o arquivo <code>.env</code> foi criado agora, gere a chave da aplicação:</p>
            <pre class="bg-body-tertiary border rounded-3 p-3 overflow-auto"><code>php artisan key:generate</code></pre>
            <p>Configure a conexão MySQL no arquivo <code>.env</code>. Em um banco vazio, crie as tabelas, insira os dados de demonstração e inicie a aplicação:</p>
            <pre class="bg-body-tertiary border rounded-3 p-3 overflow-auto"><code>php artisan migrate
php artisan db:seed
php artisan serve</code></pre>
            <p>Para reconstruir um banco descartável, use <code>php artisan migrate:fresh --drop-views --seed</code>. Esse comando apaga os dados existentes e a view do relatório antes de recriá-los.</p>

        </section>
    </main>

</div>
@include('extras.backlog')
@endsection

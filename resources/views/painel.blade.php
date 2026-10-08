@extends('layout')
@section('content')


<div class="col-lg-10 col-xl-10 mx-auto py-md-4">
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-3 border-bottom">
        <span class="fs-4">Painel de Livros</span>
        <a href="{{ url('/documentacao') }}" class="btn btn-outline-primary btn-sm">Documentação</a>
    </header>

    <div class="container">

        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Livros</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cadastros-tab" data-bs-toggle="tab" data-bs-target="#cadastros-tab-pane" type="button" role="tab" aria-controls="cadastros-tab-pane" aria-selected="false">Cadastros</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Relatório</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                <div class="row">
                    <!-- Adicionar Livros -->

                    <div class="col-12">
                        <button type="button" id="add_livros" onclick="abrirModalLivro()" class="btn btn-primary btn-sm btn-add">Adicionar livro</button>

                        <div class="card ">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Editora</th>
                                        <th>Edição</th>
                                        <th>Valor</th>
                                        <th>Ano</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($livros as $livro)
                                    <tr id="{{$livro->Codl}}">
                                        <td>{{$livro->Titulo}}</td>
                                        <td>{{$livro->Editora}}</td>
                                        <td>{{$livro->Edicao}}</td>
                                        <td>R$ {{ number_format($livro->Valor, 2, ',', '.') }}</td>
                                        <td>{{$livro->AnoPublicacao}}</td>
                                        <td>
                                            <a href="javascript:" onclick="abrirModalLivro('{{$livro->Codl}}')"><i class="bi bi-pencil me-2"></i></a>
                                            <a href="javascript:" onclick="confirmaDeletar('livros','{{$livro->Codl}}')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="cadastros-tab-pane" role="tabpanel" aria-labelledby="cadastros-tab" tabindex="0">
                <div class="row">

                    <!-- Adicionar Autores -->
                    <div class="col-6">
                        <button type="button" id="add_autores" onclick="abrirModalAutor()" class="btn btn-primary btn-sm btn-add">Adicionar autor</button>
                        <div class="card">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nome</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($autores as $autor)
                                    <tr id="{{$autor->CodAu}}">
                                        <td style="width: 70%;">{{$autor->Nome}}</td>
                                        <td style="width: 30%;">
                                            <i onclick="abrirModalAutor('{{$autor->CodAu}}')" class="bi bi-pencil me-2"></i>
                                            <i onclick="confirmaDeletar('autors','{{$autor->CodAu}}')" class="bi bi-trash"></i>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Adicionar Assunto -->
                    <div class="col-6">
                        <button type="button" id="add_assunto" onclick="abrirModalAssunto()" class="btn btn-primary btn-sm btn-add">Adicionar assunto</button>
                        <div class="card">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="2">Descrição</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assuntos as $assunto)
                                    <tr id="{{$assunto->CodAs}}">
                                        <td style="width: 70%;">{{$assunto->Descricao}}</td>
                                        <td style="width: 30%;">
                                            <i onclick="abrirModalAssunto('{{$assunto->CodAs}}')" class="bi bi-pencil me-2"></i>
                                            <i onclick="confirmaDeletar('assuntos','{{$assunto->CodAs}}')" class="bi bi-trash"></i>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <form id="formRelatorio" class="input-group mb-3" style="max-width: 28rem">
                    <input type="search" name="autor" placeholder="Buscar por autor" class="form-control" id="buscar_relatorio" aria-label="Buscar por autor">
                    <button id="botaoBuscarRelatorio" class="btn btn-outline-secondary" type="submit">Buscar</button>
                </form>
                <div id="erroRelatorio" class="text-danger mb-2" role="alert" aria-live="polite"></div>

                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Livro</th>
                                    <th>Assuntos</th>
                                    <th>Editora</th>
                                    <th>Edição</th>
                                    <th>Ano</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody id="resultadosRelatorio">
                                <tr>
                                    <td colspan="6">Clique em Buscar para carregar o relatório.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('modal.livros')
@include('modal.autores')
@include('modal.assuntos')
@include('modal.confirmar')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const id = sessionStorage.getItem('abaAposSalvar');
        sessionStorage.removeItem('abaAposSalvar');

        const aba = id && document.getElementById(id);
        if (aba) {
            bootstrap.Tab.getOrCreateInstance(aba).show();
        }
    });
</script>
@endsection

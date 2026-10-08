$(function () {
    const form = $('#formRelatorio');

    if (!form.length) {
        return;
    }

    const resultados = $('#resultadosRelatorio');
    const botao = $('#botaoBuscarRelatorio');
    const erros = $('#erroRelatorio');
    const moeda = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

    function mensagem(texto) {
        resultados.empty().append($('<tr>').append($('<td>', { colspan: 6 }).text(texto)));
    }

    form.on('submit', function (event) {
        event.preventDefault();

        if (botao.prop('disabled')) {
            return;
        }

        botao.prop('disabled', true);
        erros.text('');
        mensagem('Buscando relatório...');

        $.ajax({
            url: '/api/relatorio/livros',
            method: 'GET',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                const grupos = response.data;

                if (!grupos.length) {
                    mensagem('Nenhum livro encontrado para este relatório.');
                    return;
                }

                resultados.empty();

                grupos.forEach(function (autor) {
                    resultados.append(
                        $('<tr>').addClass('table-secondary').append(
                            $('<th>', { colspan: 6 }).text(autor.nome)
                        )
                    );

                    autor.livros.forEach(function (livro) {
                        const linha = $('<tr>');
                        const campos = [
                            livro.titulo,
                            livro.assuntos || '—',
                            livro.editora,
                            livro.edicao,
                            livro.ano,
                            livro.valor === null ? '—' : moeda.format(Number(livro.valor)),
                        ];

                        campos.forEach(function (campo) {
                            linha.append($('<td>').text(campo));
                        });

                        resultados.append(linha);
                    });
                });
            },
            error: function (xhr) {
                mensagem('Não foi possível carregar o relatório.');
                const validacao = xhr.responseJSON?.errors;
                erros.text(validacao ? Object.values(validacao).flat().join(' ') : 'Tente novamente.');
            },
            complete: function () {
                botao.prop('disabled', false);
            },
        });
    });
});

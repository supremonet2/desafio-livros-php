$(function () {
    const form = $('#formRelatorio');

    if (!form.length) {
        return;
    }

    const resultados = $('#resultadosRelatorio');
    const botao = $('#botaoBuscarRelatorio');
    const linkPdf = $('#linkRelatorioPdf');
    const erros = $('#erroRelatorio');
    const moeda = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

    form.find('input[name="autor"]').on('input', function () {
        linkPdf.addClass('d-none').attr('href', '#');
    });

    function mensagem(texto) {
        resultados.empty().append($('<tr>').append($('<td>', { colspan: 6 }).text(texto)));
    }

    form.on('submit', function (event) {
        event.preventDefault();

        if (botao.prop('disabled')) {
            return;
        }

        botao.prop('disabled', true);
        linkPdf.addClass('d-none').attr('href', '#');
        erros.text('');
        mensagem('Buscando relatório...');

        const filtro = form.serialize();

        $.ajax({
            url: '/api/relatorio/livros',
            method: 'GET',
            data: filtro,
            dataType: 'json',
            success: function (response) {
                if (form.serialize() !== filtro) {
                    mensagem('Filtro alterado. Clique em Buscar novamente.');
                    return;
                }

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

                linkPdf.attr('href', form.data('pdf-url') + '?' + filtro).removeClass('d-none');
            },
            error: function (xhr) {
                if (form.serialize() !== filtro) {
                    mensagem('Filtro alterado. Clique em Buscar novamente.');
                    return;
                }

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

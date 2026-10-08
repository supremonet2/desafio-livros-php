<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatório de livros por autor</title>
    <style>
        @page { margin: 22mm 16mm; }
        body { color: #212529; font-family: "DejaVu Sans", sans-serif; font-size: 10px; }
        h1 { font-size: 19px; margin: 0 0 6px; }
        h2 { background: #e9ecef; font-size: 12px; margin: 18px 0 0; padding: 8px; page-break-after: avoid; }
        .subtitulo { color: #555; margin-bottom: 22px; }
        table { border-collapse: collapse; margin-bottom: 14px; table-layout: fixed; width: 100%; }
        th, td { border: 1px solid #ced4da; padding: 7px; text-align: left; vertical-align: top; word-wrap: break-word; }
        th { background: #f8f9fa; }
        tr { page-break-inside: avoid; }
        .numero { text-align: right; white-space: nowrap; }
        .vazio { border: 1px solid #ced4da; padding: 14px; }
    </style>
</head>
<body>
    <h1>Relatório de livros por autor</h1>
    <div class="subtitulo">
        Gerado em {{ now()->timezone('America/Bahia')->format('d/m/Y H:i') }} (horário da Bahia)
        @if ($termoAutor !== '')
            · Filtro por autor: {{ $termoAutor }}
        @endif
    </div>

    @forelse ($relatorio as $autor)
        <h2>Autor: {{ $autor['nome'] }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 25%">Livro</th>
                    <th style="width: 25%">Assuntos</th>
                    <th style="width: 18%">Editora</th>
                    <th style="width: 9%">Edição</th>
                    <th style="width: 9%">Ano</th>
                    <th style="width: 14%">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($autor['livros'] as $livro)
                    <tr>
                        <td>{{ $livro['titulo'] }}</td>
                        <td>{{ $livro['assuntos'] ?: '—' }}</td>
                        <td>{{ $livro['editora'] }}</td>
                        <td class="numero">{{ $livro['edicao'] }}</td>
                        <td class="numero">{{ $livro['ano'] }}</td>
                        <td class="numero">{{ $livro['valor'] === null ? '—' : 'R$ '.number_format((float) $livro['valor'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p class="vazio">Nenhum livro encontrado para este relatório.</p>
    @endforelse
</body>
</html>

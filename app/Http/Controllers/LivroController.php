<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LivroController extends Controller
{

    public function index()
    {
        $livros = Livro::get();
        $autores = Autor::get();
        $assuntos = Assunto::get();

        // -- Responde direto na api ---
        if (request()->is('api/*')) {
            return response()->json(['data' => $livros]);
        }

        return view('painel', [
            'livros' => $livros,
            'autores' => $autores,
            'assuntos' => $assuntos,
        ]);
    }

    public function store(StoreLivroRequest $request)
    {
        $data = $request->validated();

        $livro = DB::transaction(function () use ($data): Livro {
            $livro = Livro::create([
                'Codl' => $data['Codl'],
                'Titulo' => $data['titulo'],
                'Editora' => $data['editora'],
                'Edicao' => $data['edicao'],
                'AnoPublicacao' => $data['ano'],
                'Valor' => $data['valor'],
            ]);

            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);

            return $livro;
        });

        return response()->json(['data' => $livro->load(['autores', 'assuntos'])], 201);
    }

    public function show(Livro $livro)
    {
        return response()->json(['data' => $livro->load(['autores', 'assuntos'])]);
    }

    public function update(UpdateLivroRequest $request, Livro $livro)
    {
        $data = $request->validated();

        DB::transaction(function () use ($livro, $data): void {
            $livro->update([
                'Titulo' => $data['titulo'],
                'Editora' => $data['editora'],
                'Edicao' => $data['edicao'],
                'AnoPublicacao' => $data['ano'],
                'Valor' => $data['valor'],
            ]);

            $livro->autores()->sync($data['autores']);
            $livro->assuntos()->sync($data['assuntos']);
        });

        return response()->json(['data' => $livro->load(['autores', 'assuntos'])]);
    }

    public function destroy(Livro $livro)
    {
        $del = DB::transaction(function () use ($livro): bool {
            $livro->autores()->detach();
            $livro->assuntos()->detach();

            return (bool) $livro->delete();
        });

        if (! $del) {
            return response()->json(['message' => 'Não foi possível excluir o livro.'], 500);
        }

        return response()->noContent();
    }

    public function buscarRelatorio(Request $request)
    {
        $termoAutor = $this->termoAutorRelatorio($request);
        return response()->json(['data' => $this->consultarRelatorio($termoAutor)]);
    }

    public function baixarRelatorioPdf(Request $request)
    {
        $termoAutor = $this->termoAutorRelatorio($request);
        return Pdf::loadView('extras.relatorio', [
            'relatorio' => $this->consultarRelatorio($termoAutor),
            'termoAutor' => $termoAutor,
        ])->setPaper('a4', 'landscape')->download('relatorio-livros-por-autor.pdf');
    }

    private function termoAutorRelatorio(Request $request): string
    {
        $request->validate(['autor' => ['nullable', 'string', 'max:40']]);
        $termoAutor = $request->query('autor');
        return is_string($termoAutor) ? trim($termoAutor) : '';
    }

    private function consultarRelatorio(string $termoAutor): Collection
    {
        $linhasRelatorio = DB::table('vw_relatorio_livros_por_autor')
            ->when($termoAutor !== '', fn($query) => $query->where('autor_nome', 'like', '%' . $termoAutor . '%'))
            ->orderBy('autor_nome')
            ->orderBy('livro_titulo')
            ->orderBy('assunto_descricao')
            ->get();

        return $linhasRelatorio->groupBy('autor_codigo')->map(function (Collection $linhasAutor): array {
            return [
                'nome' => $linhasAutor->first()->autor_nome,
                'livros' => $linhasAutor->groupBy('livro_codigo')->map(function (Collection $linhasLivro): array {
                    $livro = $linhasLivro->first();

                    return [
                        'titulo' => $livro->livro_titulo,
                        'editora' => $livro->editora,
                        'edicao' => $livro->edicao,
                        'ano' => $livro->ano_publicacao,
                        'valor' => $livro->valor,
                        'assuntos' => $linhasLivro->pluck('assunto_descricao')->filter()->unique()->implode(', '),
                    ];
                })->values(),
            ];
        })->values();
    }

    // -- Pagimnas extras --
    public function home()
    {
        return view('extras.inicio');
    }

    public function documentacao()
    {
        return view('extras.docs');
    }
}

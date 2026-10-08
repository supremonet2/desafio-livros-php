<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
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
        $request->validate(['autor' => ['nullable', 'string', 'max:40']]);

        $termoAutor = $request->query('autor');
        $termoAutor = is_string($termoAutor) ? trim($termoAutor) : '';

        $linhasRelatorio = DB::table('vw_relatorio_livros_por_autor')
            ->when($termoAutor !== '', fn($query) => $query->where('autor_nome', 'like', '%' . $termoAutor . '%'))
            ->orderBy('autor_nome')
            ->orderBy('livro_titulo')
            ->orderBy('assunto_descricao')
            ->get();

        $relatorio = $linhasRelatorio->groupBy('autor_codigo')->map(function (Collection $linhasAutor): array {
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

        return response()->json(['data' => $relatorio]);
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

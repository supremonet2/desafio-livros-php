<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutorRequest;
use App\Http\Requests\UpdateAutorRequest;
use App\Models\Autor;

class AutorController extends Controller
{
    public function index()
    {
        $autores = Autor::get();
        return response()->json(['data' => $autores]);
    }

    public function store(StoreAutorRequest $request)
    {
        $data = $request->validated();
        Autor::create($data);
        return response()->json(['data' => $data]);
    }

    public function show(Autor $autor)
    {
        return response()->json(['data' => $autor]);
    }

    public function update(UpdateAutorRequest $request, Autor $autor)
    {
        $data = $request->validated();
        $autor->update($data);
        return response()->json(['data' => $autor]);
    }

    public function destroy(Autor $autor)
    {
        if ($autor->livros()->exists()) {
            return response()->json(['message' => 'Não é possível excluir o autor porque ele está vinculado a um livro.'], 409);
        }
        $del = $autor->delete();
        if (! $del) {
            return response()->json(['message' => 'Não foi possível excluir o Autor.'], 500);
        }
        return response()->noContent();
    }
}

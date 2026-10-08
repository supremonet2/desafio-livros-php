<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use App\Models\Assunto;

class AssuntoController extends Controller
{
    public function index()
    {
        $assuntos = Assunto::get();
        return response()->json(['data' => $assuntos]);
    }

    public function store(StoreAssuntoRequest $request)
    {
        $data = $request->validated();
        Assunto::create($data);
        return response()->json(['data' => $data]);
    }

    public function show(Assunto $assunto)
    {
        return response()->json(['data' => $assunto]);
    }

    public function update(UpdateAssuntoRequest $request, Assunto $assunto)
    {
        $data = $request->validated();
        $assunto->update($data);
        return response()->json(['data' => $assunto]);
    }

    public function destroy(Assunto $assunto)
    {
        if ($assunto->livros()->exists()) {
            return response()->json(['message' => 'Não é possível excluir o assunto porque ele está vinculado a um livro.'], 409);
        }

        $del = $assunto->delete();
        if (! $del) {
            return response()->json(['message' => 'Não foi possível excluir o assunto.'], 500);
        }

        return response()->noContent();
    }
}

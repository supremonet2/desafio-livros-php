<?php

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoLivrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $autores = [
                201 => 'Ana Martins',
                202 => 'Bruno Lima',
                203 => 'Carla Nogueira',
                204 => 'Diego Rocha',
                205 => 'Elisa Costa',
            ];

            foreach ($autores as $codigo => $nome) {
                Autor::create(['CodAu' => $codigo, 'Nome' => $nome]);
            }

            $assuntos = [
                301 => 'Literatura',
                302 => 'História',
                303 => 'Tecnologia',
                304 => 'Culinária',
                305 => 'Educação',
            ];

            foreach ($assuntos as $codigo => $descricao) {
                Assunto::create(['CodAs' => $codigo, 'Descricao' => $descricao]);
            }

            $livros = [
                [
                    'dados' => ['Codl' => 1001, 'Titulo' => 'Caminhos da Leitura', 'Editora' => 'Aurora', 'Edicao' => 1, 'AnoPublicacao' => '2021', 'Valor' => '39.90'],
                    'autores' => [201, 203],
                    'assuntos' => [301, 305],
                ],
                [
                    'dados' => ['Codl' => 1002, 'Titulo' => 'Sabores do Brasil', 'Editora' => 'Horizonte', 'Edicao' => 2, 'AnoPublicacao' => '2022', 'Valor' => '54.50'],
                    'autores' => [202],
                    'assuntos' => [302, 304],
                ],
                [
                    'dados' => ['Codl' => 1003, 'Titulo' => 'Tecnologia no Dia a Dia', 'Editora' => 'Ponto Norte', 'Edicao' => 1, 'AnoPublicacao' => '2023', 'Valor' => '69.00'],
                    'autores' => [204, 205],
                    'assuntos' => [303, 305],
                ],
                [
                    'dados' => ['Codl' => 1004, 'Titulo' => 'Memórias da Cidade', 'Editora' => 'Travessia', 'Edicao' => 3, 'AnoPublicacao' => '2020', 'Valor' => '45.90'],
                    'autores' => [203],
                    'assuntos' => [301, 302],
                ],
                [
                    'dados' => ['Codl' => 1005, 'Titulo' => 'Aprender em Conjunto', 'Editora' => 'Aurora', 'Edicao' => 1, 'AnoPublicacao' => '2024', 'Valor' => '62.00'],
                    'autores' => [201, 205],
                    'assuntos' => [303, 305],
                ],
            ];

            foreach ($livros as $dadosLivro) {
                $livro = Livro::create($dadosLivro['dados']);
                $livro->autores()->sync($dadosLivro['autores']);
                $livro->assuntos()->sync($dadosLivro['assuntos']);
            }
        });
    }
}

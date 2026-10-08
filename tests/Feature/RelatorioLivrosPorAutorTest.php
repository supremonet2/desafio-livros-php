<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RelatorioLivrosPorAutorTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_view_includes_each_author_and_subject_of_a_book(): void
    {
        $this->createReportData();

        $rows = DB::table('vw_relatorio_livros_por_autor')
            ->where('livro_codigo', 10)
            ->orderBy('autor_codigo')
            ->orderBy('assunto_codigo')
            ->get();

        $this->assertSame([233, 233, 234, 234], $rows->pluck('autor_codigo')->all());
        $this->assertSame([7, 8, 7, 8], $rows->pluck('assunto_codigo')->all());
        $this->assertSame(['Livro Azul'], $rows->pluck('livro_titulo')->unique()->values()->all());
    }

    public function test_report_groups_the_book_under_both_authors_without_repeating_it_per_subject(): void
    {
        $this->createReportData();

        $response = $this->getJson('/api/relatorio/livros');

        $response->assertOk()
            ->assertJsonPath('data.0.nome', 'Maria')
            ->assertJsonPath('data.0.livros.0.titulo', 'Livro Azul')
            ->assertJsonPath('data.0.livros.0.assuntos', 'Culinária, História')
            ->assertJsonPath('data.1.nome', 'Ray')
            ->assertJsonPath('data.1.livros.0.titulo', 'Livro Azul');

        $this->assertCount(2, $response->json('data'));
        $this->assertCount(1, $response->json('data.0.livros'));
        $this->assertCount(1, $response->json('data.1.livros'));
    }

    public function test_report_can_be_filtered_by_author_name(): void
    {
        $this->createReportData();

        $this->getJson('/api/relatorio/livros?autor=Ray')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nome', 'Ray');
    }

    public function test_index_does_not_load_the_report_before_search(): void
    {
        $this->createReportData();

        $this->get('/livros')
            ->assertOk()
            ->assertViewMissing('relatorio')
            ->assertSeeText('Clique em Buscar para carregar o relatório.');
    }

    private function createReportData(): void
    {
        DB::table('livros')->insert([
            'Codl' => 10,
            'Titulo' => 'Livro Azul',
            'Editora' => 'Editora Globo',
            'Edicao' => 2,
            'AnoPublicacao' => '2023',
            'Valor' => '12.00',
        ]);
        DB::table('autors')->insert([
            ['CodAu' => 233, 'Nome' => 'Ray'],
            ['CodAu' => 234, 'Nome' => 'Maria'],
        ]);
        DB::table('assuntos')->insert([
            ['CodAs' => 7, 'Descricao' => 'Culinária'],
            ['CodAs' => 8, 'Descricao' => 'História'],
        ]);
        DB::table('Livro_Autor')->insert([
            ['Livro_Codl' => 10, 'Autor_CodAu' => 233],
            ['Livro_Codl' => 10, 'Autor_CodAu' => 234],
        ]);
        DB::table('Livro_Assunto')->insert([
            ['Livro_Codl' => 10, 'Assunto_CodAs' => 7],
            ['Livro_Codl' => 10, 'Assunto_CodAs' => 8],
        ]);
    }
}

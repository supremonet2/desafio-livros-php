<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE VIEW vw_relatorio_livros_por_autor AS
            SELECT
                a.CodAu AS autor_codigo,
                a.Nome AS autor_nome,
                l.Codl AS livro_codigo,
                l.Titulo AS livro_titulo,
                l.Editora AS editora,
                l.Edicao AS edicao,
                l.AnoPublicacao AS ano_publicacao,
                l.Valor AS valor,
                s.CodAs AS assunto_codigo,
                s.Descricao AS assunto_descricao
            FROM Livro_Autor AS la
            INNER JOIN autors AS a ON a.CodAu = la.Autor_CodAu
            INNER JOIN livros AS l ON l.Codl = la.Livro_Codl
            LEFT JOIN Livro_Assunto AS ls ON ls.Livro_Codl = l.Codl
            LEFT JOIN assuntos AS s ON s.CodAs = ls.Assunto_CodAs
            SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_relatorio_livros_por_autor');
    }
};

# Cadastro de livros

Aplicação web do desafio técnico de PHP para cadastrar, consultar, editar e excluir livros, autores e assuntos. Um livro pode ter vários autores e vários assuntos. O painel também oferece uma busca de relatório agrupado por autor, alimentada por uma view SQL.

## Tecnologias e Requisitos

- PHP 8.3 ou superior e Laravel 13 para rotas, validação, persistência e API.
- MySQL para a aplicação.
- Blade, Bootstrap 5, Bootstrap Icons, jQuery e Tom Select para a interface. O layout atual carrega essas bibliotecas por CDN.
- `barryvdh/laravel-dompdf` para gerar o relatório em PDF a partir de uma view Blade.

## Instalação local com MySQL

Execute os comandos a seguir na raiz do projeto. O arquivo `.env` é local e deve conter as credenciais da sua instalação, sem ser enviado junto com o código.
```
composer install
cp .env.example .env
php artisan key:generate --no-interaction
```

Crie um banco vazio no MySQL, por exemplo:
```
CREATE DATABASE livros CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

No `.env`, substitua a configuração padrão de SQLite pelos dados do seu MySQL:
```
APP_NAME=Livros
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=livros
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Depois, crie as tabelas e a view e inicie o servidor:
```
php artisan migrate --no-interaction
php artisan db:seed --no-interaction
php artisan migrate:status --no-interaction
php artisan serve --no-interaction
```

O `DatabaseSeeder` chama `DemoLivrosSeeder` e cria cinco autores, cinco assuntos e cinco livros para demonstração. Execute a semeadura em um banco recém-migrado e vazio, pois os códigos dos registros de exemplo são fixos. Abra `http://127.0.0.1:8000/` para acessar a página inicial. O painel fica em `/livros` e a documentação na aplicação em `/documentacao`.

`php artisan migrate:fresh --drop-views --seed --no-interaction`

## Banco de dados

As migrations criam as três tabelas principais e duas tabelas de vínculo:

| Objeto | Conteúdo |
| --- | --- |
| `livros` | `Codl`, título, editora, edição, ano de publicação e valor em `DECIMAL(10,2)` |
| `autors` | `CodAu` e nome do autor |
| `assuntos` | `CodAs` e descrição do assunto |
| `Livro_Autor` | Chave composta por `Livro_Codl` e `Autor_CodAu`, com chaves estrangeiras |
| `Livro_Assunto` | Chave composta por `Livro_Codl` e `Assunto_CodAs`, com chaves estrangeiras |

A migration `2026_10_07_190636_create_relatorio_livros_por_autor_view.php` cria a view `vw_relatorio_livros_por_autor`. Ela reúne os dados das três tabelas principais por meio das duas tabelas de vínculo. O projeto não utiliza procedures nem triggers. As outras migrations criam tabelas de suporte do Laravel.

## Relatório em PDF

Na aba **Relatório**, clique em **Buscar** para consultar a view do banco. Quando a busca trouxer livros, o ícone **PDF** fica disponível. Ele faz uma nova consulta à mesma view, aplica o filtro de autor da busca e baixa `relatorio-livros-por-autor.pdf`. O documento é renderizado pela Blade `resources/views/extras/relatorio.blade.php` e apresenta livros e assuntos agrupados por autor. Um livro ligado a dois autores aparece no grupo de ambos.

A rota de download é `GET /relatorio/livros/pdf?autor=nome`. O filtro `autor` é opcional e limitado a 40 caracteres. Execute `composer install` para instalar a biblioteca antes de usar a rota.

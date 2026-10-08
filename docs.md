# Desenvolvimento

Este arquivo registra a construção do projeto e as pendências para a apresentação do desafio. Inclui os pontos adicionais do e-mail de convite. Estado conferido no código em 8 de outubro de 2026; prazo informado: **09/10/2026**.

## Etapas realizadas

1. **Análise do desafio e do DER:** identificação dos CRUDs de Livro, Autor e Assunto, das relações muitos-para-muitos, do campo de valor em reais e do relatório agrupado por autor a partir de uma view do banco.
2. **Modelo de dados:** criação das migrations de `livros`, `autors`, `assuntos`, `Livro_Autor` e `Livro_Assunto`, com as chaves e relações do modelo fornecido. A criação de `livros` já inclui `Valor` como `DECIMAL(10,2)`, e a de `Livro_Assunto` já usa os nomes corretos das chaves. Outra migration cria a view `vw_relatorio_livros_por_autor`.
3. **Estrutura Laravel:** criação dos models, controllers e Form Requests. Os códigos de Livro, Autor e Assunto são informados no cadastro, sem geração automática.
4. **Layout e entrada:** criação de um layout Blade compartilhado com Bootstrap, Bootstrap Icons, jQuery e Tom Select; adaptação de `welcome.blade.php` com acesso ao painel.
5. **Painel:** criação de `painel.blade.php` com três abas: Livros, Cadastros e Relatório. `LivroController@index` carrega livros, autores e assuntos para a primeira renderização. Livros ocupa a largura da aba; Autores e Assuntos aparecem em duas colunas na aba Cadastros.
6. **Modais:** criação de formulários separados para Livro, Autor e Assunto e de um modal de confirmação de exclusão compartilhado. O formulário de Livro permite selecionar vários autores e assuntos; a edição carrega os vínculos existentes.
7. **Formatação e validação de Livro:** inclusão de código manual, título, editora, edição, ano, valor e vínculos. O ano usa um seletor de nove opções por período. O valor tem máscara brasileira, é normalizado no Form Request e aparece formatado na listagem.
8. **Operações dos cadastros:** ações de cadastro, consulta, atualização e exclusão ligadas aos modais por AJAX. Livro e seus vínculos são gravados em transações; as exclusões de Autor e Assunto vinculados a livros recebem resposta específica de conflito. Após uma operação bem-sucedida, a interface recarrega a página.
9. **Relatório:** a migration cria a view SQL `vw_relatorio_livros_por_autor`. Uma ação separada de `index()` consulta essa view, filtra por nome de autor e agrupa livros e assuntos por autor. A aba começa sem dados; o botão Buscar chama a API e preenche a tabela sem recarregar a página. Um livro com dois autores aparece no grupo de ambos. Após uma busca com resultados, o botão PDF permite baixar o relatório gerado com DomPDF a partir de uma nova consulta à mesma view.
10. **Instalação limpa:** o banco MySQL `livros` foi recriado e as dez migrations consolidadas rodaram em ordem, incluindo a view. As chaves manuais e os vínculos foram conferidos em uma transação de teste revertida.


## Decisão de arquitetura e apresentação

A rota web `/livros` renderiza as listas iniciais. O JavaScript usa a API para consultar um registro, salvar, atualizar e excluir. A busca do relatório ocorre somente após o clique em Buscar e lê a view SQL.

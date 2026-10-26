# Roteiro do professor — Models e Migrations no projeto Urna Escola

## Como usar a apresentação

Abra `apresentacao-models-migrations.html` no navegador.

- Setas `←` e `→`, espaço ou Enter: navegar.
- Tecla `F`: entrar ou sair da tela cheia.
- `Home` e `End`: primeiro e último slide.
- `Ctrl + P`: imprimir ou salvar em PDF no formato paisagem.
- Em celular ou tablet, deslize horizontalmente.

O material foi pensado para cinco aulas de aproximadamente 60 minutos. Se a turma precisar de mais tempo para codificar, transforme cada prática em uma aula adicional.

## Preparação

Antes da primeira aula:

1. Confirme que o projeto executa com `php artisan serve`.
2. Execute `php artisan migrate --seed` em um banco exclusivo da aula.
3. Abra o MySQL Workbench ou outra ferramenta para visualizar as tabelas.
4. Deixe um terminal aberto na pasta do projeto.
5. Para demonstrações destrutivas, use uma cópia do banco.

Arquivos mais usados durante as aulas:

- `routes/web.php`
- `app/Models/Candidato.php`
- `app/Models/Eleitor.php`
- `app/Models/Voto.php`
- `database/migrations/2026_10_26_000002_create_candidatos_table.php`
- `database/migrations/2026_10_26_000004_create_votos_table.php`
- `app/Http/Controllers/UrnaController.php`

## Aula 1 — Arquitetura MVC

### Objetivo

Compreender a separação entre Model, View e Controller e reconhecer as entidades do projeto.

### Sugestão de tempo

- 10 min: pergunta disparadora e demonstração da urna.
- 20 min: explicação de MVC e caminho da requisição.
- 15 min: exploração das pastas do Laravel.
- 15 min: atividade de identificação das entidades.

### Demonstração

1. Acesse a urna e registre um voto de demonstração.
2. Mostre a rota `urna.confirmar` em `routes/web.php`.
3. Abra o método `confirmar()` no `UrnaController`.
4. Mostre o Model `Voto` e a tabela `votos` no MySQL.
5. Refaça verbalmente o caminho: View → Rota → Controller → Model → Banco.

### Perguntas para a turma

- A tela deveria executar SQL diretamente? Por quê?
- Em qual camada fica a aparência da urna?
- Qual camada decide se um número é válido ou nulo?
- “Candidato” é uma tela ou uma entidade do domínio?

### Evidência de aprendizagem

Cada dupla explica uma entidade usando a estrutura: “Ela representa…, possui os dados… e permite as ações…”.

## Aula 2 — Migrations

### Objetivo

Criar e desfazer alterações na estrutura do banco usando migrations.

### Sugestão de tempo

- 15 min: conceito, `up()` e `down()`.
- 15 min: tipos e restrições.
- 10 min: demonstração no terminal.
- 20 min: prática da tabela de locais de votação.

### Demonstração guiada

Crie uma migration temporária:

```powershell
php artisan make:migration create_locais_votacao_table
```

Implemente:

```php
Schema::create('locais_votacao', function (Blueprint $table) {
    $table->id();
    $table->string('nome', 100);
    $table->string('sala', 30);
    $table->boolean('ativo')->default(true);
    $table->timestamps();
});
```

Execute e confira:

```powershell
php artisan migrate
php artisan migrate:status
```

Depois demonstre `php artisan migrate:rollback`. Explique que o método `down()` precisa ser o inverso do `up()`.

### Pontos que costumam confundir iniciantes

- Migration cria a estrutura; Seeder cria dados de exemplo.
- `nullable()` não significa que o campo sempre ficará vazio, apenas que pode ficar.
- `unique()` é uma regra garantida pelo banco, não apenas pela tela.
- O nome da migration ajuda a comunicar sua finalidade.
- Não edite uma migration já executada em produção; crie outra para alterar a tabela.

### Resposta esperada do exercício

O aluno deve produzir uma migration executável e reversível. Avalie principalmente a escolha dos tipos e o método `down()`.

## Aula 3 — Models e Eloquent

### Objetivo

Usar Models para criar, consultar, atualizar e excluir registros.

### Sugestão de tempo

- 15 min: ORM e mapeamento objeto-relacional.
- 15 min: `$table`, `$fillable` e casts.
- 15 min: CRUD com Eloquent.
- 15 min: laboratório no Tinker.

### Demonstração no Tinker

```powershell
php artisan tinker
```

```php
use App\Models\Candidato;

Candidato::all();
Candidato::where('ativo', true)->get();

$candidato = Candidato::create([
    'nome' => 'Candidato da Turma',
    'numero' => '78',
    'partido' => 'Código Livre',
    'ativo' => true,
]);

$candidato->update(['partido' => 'Aprender PHP']);
$candidato->delete();
```

### Analogia útil

- Tabela: uma planilha inteira.
- Registro: uma linha da planilha.
- Model: a classe PHP que sabe trabalhar com essas linhas.
- Objeto: uma linha específica transformada em algo que o PHP manipula.

### Pontos que costumam confundir iniciantes

- O Model não cria a tabela; a migration faz isso.
- `$fillable` não lista obrigatoriedade. Ele controla preenchimento em massa.
- Validação fica no fluxo da requisição; tipo de coluna é uma garantia adicional do banco.
- `first()` retorna um objeto ou `null`; `get()` retorna uma coleção.

## Aula 4 — Relacionamentos

### Objetivo

Entender chaves estrangeiras e relações `hasMany`/`belongsTo`.

### Sugestão de tempo

- 15 min: desenho das tabelas no quadro.
- 15 min: implementação nos Models.
- 15 min: consultas relacionadas.
- 15 min: atividade e discussão sobre sigilo.

### Demonstração

```php
$candidato = Candidato::where('numero', '12')->first();
$candidato->votos()->count();
$candidato->votos;

$voto = Voto::where('tipo', 'valido')->first();
$voto->candidato?->nome;
```

Mostre que um voto branco possui `candidato_id = null`. Em seguida, pergunte por que a tabela `votos` não possui `eleitor_id`.

### Respostas do desafio de consultas

```php
Candidato::orderBy('numero')->get();

Candidato::withCount([
    'votos' => fn ($query) => $query->where('tipo', 'valido'),
])->get();

Voto::where('tipo', 'branco')->count();

Eleitor::whereNull('votou_em')->get();
```

## Aula 5 — Integração e transação

### Objetivo

Explicar o fluxo completo do voto e por que operações relacionadas devem usar transação.

### Sugestão de tempo

- 15 min: acompanhar o fluxo completo no código.
- 15 min: validação e voto nulo.
- 15 min: transação e `lockForUpdate()`.
- 15 min: início do projeto final.

### Experimento didático

Dentro da transação, comente temporariamente a criação do voto e observe que apenas marcar o eleitor seria um erro de consistência. Depois explique:

- sem transação, uma etapa pode funcionar e a outra falhar;
- com transação, ou as duas mudanças são confirmadas, ou nenhuma é;
- `lockForUpdate()` evita que duas requisições usem o mesmo eleitor ao mesmo tempo.

Não mantenha a alteração temporária. Restaure o código ao terminar.

### Projeto final

O desafio “Turma” pode ser realizado em grupos de três pessoas:

- pessoa 1: migration e banco;
- pessoa 2: Model e relacionamento;
- pessoa 3: Controller, View e testes;
- todos: apresentação e revisão do código.

## Avaliação rápida

Peça aos alunos que respondam sem consultar o material:

1. Migration descreve a estrutura; Model representa e manipula os dados.
2. `up()` aplica e `down()` desfaz uma alteração.
3. `$fillable` define campos permitidos no preenchimento em massa.
4. `hasMany` indica que um registro se relaciona com vários do outro Model.
5. O voto não possui `eleitor_id` para preservar o sigilo.
6. Transação é usada quando várias mudanças precisam funcionar como uma única operação.

## Continuação sugerida

Depois destas aulas, os próximos assuntos naturais são:

- validação com Form Requests;
- seeders e factories;
- testes de integração;
- autenticação e middleware;
- paginação e filtros;
- princípios de segurança e proteção de dados.

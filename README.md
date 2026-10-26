# Urna Escola

Simulação educacional de urna eletrônica construída com **Laravel 12**, **PHP 8.2+**, **Blade** e **MySQL**, seguindo uma arquitetura MVC monolítica.

> Este projeto foi criado para aulas de programação e não deve ser utilizado em eleições oficiais.

## Funcionalidades

- página pública com a situação da eleição;
- identificação do eleitor por código único de seis dígitos;
- teclado numérico semelhante a uma urna;
- consulta e exibição do candidato;
- votos válidos, brancos e nulos;
- voto secreto: a tabela de votos não armazena o eleitor;
- bloqueio de segundo voto com transação e trava no registro do eleitor;
- autenticação administrativa;
- cadastro, edição, ativação e exclusão de candidatos;
- envio de foto do candidato;
- cadastro e busca de eleitores;
- abertura e encerramento da votação;
- painel com indicadores;
- apuração com totais, percentuais e relatório para impressão;
- interface responsiva e mensagens em português;
- testes automatizados dos principais fluxos.

## Organização MVC

- `app/Models`: entidades e relacionamentos do banco;
- `app/Http/Controllers`: regras dos fluxos público e administrativo;
- `resources/views`: páginas Blade;
- `database/migrations`: estrutura das tabelas;
- `database/seeders`: usuário, candidatos e eleitores de demonstração;
- `routes/web.php`: rotas da aplicação;
- `public/css` e `public/js`: estilo e interação do teclado.

## Materiais didáticos

Na pasta `materiais` há duas apresentações HTML que funcionam offline:

- `apresentacao-models-migrations.html`: 36 slides focados em Models, migrations e Eloquent;
- `apresentacao-projeto-completo.html`: 62 slides sobre todo o projeto, organizados em oito aulas.

Também estão disponíveis:

- `roteiro-do-professor.md`: apoio para as aulas de Models e migrations;
- `plano-didatico-projeto-completo.md`: objetivos, cronograma, práticas, dificuldades comuns, avaliação e projeto final.

Nas apresentações, use as setas para navegar, `F` para tela cheia e `Ctrl + P` para exportar em PDF. Na apresentação completa, a tecla `N` mostra ou oculta as notas do professor.

## Requisitos

- PHP 8.2 ou superior;
- extensões `pdo_mysql`, `mbstring`, `openssl`, `fileinfo` e `curl`;
- Composer;
- MySQL 8 ou MariaDB compatível.

No Windows, habilite `extension=pdo_mysql` no `php.ini` usado pelo terminal. Verifique com:

```powershell
php -m | Select-String pdo_mysql
```

## Instalação

Crie o banco no MySQL:

```sql
CREATE DATABASE urna_eletronica
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Configure o arquivo `.env` se o usuário ou a senha do MySQL forem diferentes:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=urna_eletronica
DB_USERNAME=root
DB_PASSWORD=
```

Depois execute:

```powershell
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Acesse `http://127.0.0.1:8000`.

## Dados de demonstração

Administração:

- e-mail: `admin@urna.test`
- senha: `12345678`

Troque essa senha antes de expor a aplicação em uma rede.

Códigos de eleitor: `100001`, `100002`, `100003`, `100004` e `100005`.

Candidatos de exemplo: números `12`, `34` e `56`.

## Testes

Os testes usam SQLite em memória e não alteram o banco MySQL:

```powershell
php artisan test
```

Para padronizar o código PHP:

```powershell
vendor\bin\pint
```

## Recomeçar a demonstração

Em ambiente de aula, para apagar os dados e recriar os exemplos:

```powershell
php artisan migrate:fresh --seed
```

Esse comando remove todas as tabelas do banco configurado. Use apenas no banco da atividade.

## Estrutura do banco

- `users`: administradores;
- `configuracoes_eleicao`: título e situação da eleição;
- `candidatos`: nome, número, partido, foto e situação;
- `eleitores`: nome, código e horário em que votou;
- `votos`: tipo, número digitado e candidato, sem referência ao eleitor.

O registro do voto e a marcação do eleitor acontecem na mesma transação. O eleitor é bloqueado durante essa operação para evitar dois votos simultâneos com o mesmo código.

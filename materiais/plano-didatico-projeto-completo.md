# Plano didático — Projeto completo Urna Escola

## Proposta

Curso baseado em projeto para estudantes iniciantes de programação web. Ao longo de oito encontros, a turma investiga, executa, modifica e explica uma aplicação monolítica construída com Laravel, Blade e MySQL.

A apresentação principal está em `apresentacao-projeto-completo.html`. Pressione `N` durante a apresentação para exibir notas pedagógicas do slide atual.

## Público e carga horária

- Público: iniciantes que já viram lógica de programação e noções de PHP.
- Formato sugerido: 8 encontros de 90 minutos.
- Alternativa: 16 encontros de 45–60 minutos, dividindo explicação e prática.
- Organização: duplas na exploração e trios no projeto final.

## Objetivo geral

Ao final, o estudante deverá conseguir explicar e modificar o fluxo completo de uma aplicação web MVC: da requisição HTTP à persistência no banco e da consulta à resposta exibida ao usuário.

## Metodologia

Cada aula segue um ciclo curto:

1. **Problema:** começar com uma situação observável no sistema.
2. **Previsão:** alunos dizem o que acreditam que o código faz.
3. **Demonstração:** professor executa e investiga o fluxo.
4. **Conceito:** nomear e organizar aquilo que foi observado.
5. **Prática:** alunos modificam ou consultam o projeto.
6. **Explicação:** alunos justificam suas decisões.
7. **Verificação:** teste manual ou automatizado confirma o resultado.

Evite mostrar longos blocos de código sem uma pergunta concreta. Antes de abrir um arquivo, diga qual comportamento estamos tentando compreender.

## Preparação do laboratório

Em cada computador:

- PHP 8.2 ou superior com `pdo_mysql` habilitado;
- Composer;
- MySQL em execução;
- editor de código;
- navegador moderno;
- projeto instalado e `.env` configurado.

Comandos de verificação:

```powershell
php --version
php -m | Select-String pdo_mysql
composer --version
php artisan --version
php artisan test
```

Crie um banco exclusivo para a atividade. Nunca use um banco com dados importantes ao demonstrar `migrate:fresh`.

---

## Aula 1 — Planejamento e ambiente

### Objetivos

- Diferenciar funcionalidade, regra de negócio e requisito de qualidade.
- Reconhecer os perfis de eleitor e administrador.
- Executar o projeto localmente.
- Registrar requisitos em histórias de usuário simples.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–10 min | Professor demonstra uma votação completa sem mostrar o código. |
| 10–25 min | Turma lista o que existe “por trás” das telas. |
| 25–40 min | Classificação de requisitos. |
| 40–55 min | Explicação das ferramentas e arquitetura geral. |
| 55–80 min | Instalação, execução e exploração em duplas. |
| 80–90 min | Bilhete de saída. |

### Perguntas disparadoras

- Como impedir que o mesmo código vote duas vezes?
- Onde deveria ficar a senha do banco?
- O voto precisa saber quem foi o eleitor?
- O que acontece se a internet ou o banco falhar no meio da confirmação?

### Prática

Cada dupla percorre três papéis:

1. eleitor registra um voto;
2. administrador cria um candidato;
3. observador registra telas, regras e possíveis erros.

### Evidência de aprendizagem

Bilhete de saída com:

- uma funcionalidade;
- uma regra de negócio;
- um requisito de qualidade;
- uma dúvida técnica.

### Dificuldades comuns

- `could not find driver`: extensão `pdo_mysql` não carregada.
- `using password: NO`: `DB_PASSWORD` vazio.
- `Unknown database`: banco ainda não foi criado.
- `php` não reconhecido: executável fora do PATH.

---

## Aula 2 — MVC, pastas e rotas

### Objetivos

- Explicar Model, View e Controller com exemplos do projeto.
- Localizar arquivos por responsabilidade.
- Ler uma rota e identificar verbo, URI, Controller e nome.
- Rastrear uma requisição simples.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–10 min | Revisão por perguntas. |
| 10–25 min | Desenho do fluxo HTTP no quadro. |
| 25–40 min | Exploração guiada das pastas. |
| 40–55 min | Rotas e verbos HTTP. |
| 55–80 min | Prática “rastreie uma requisição”. |
| 80–90 min | Explicação entre pares. |

### Demonstração

Execute:

```powershell
php artisan route:list --except-vendor
```

Escolha `urna.acessar` e siga:

1. formulário em `urna/identificacao.blade.php`;
2. rota em `routes/web.php`;
3. método `acessar()` em `UrnaController`;
4. consulta ao Model `Eleitor`;
5. redirecionamento para `urna.votacao`.

### Estratégia de quadro

Use cartões de cores diferentes:

- azul: entrada HTTP;
- laranja: decisão do Controller;
- verde: acesso do Model;
- roxo: resposta/View.

Peça que alunos reorganizem os cartões quando estiverem fora de ordem.

### Verificação rápida

Mostre uma rota desconhecida e peça:

- Qual verbo ela aceita?
- Qual método será executado?
- Qual nome deve ser usado em `route()`?
- Ela está protegida por autenticação?

---

## Aula 3 — Banco de dados, migrations e Models

### Objetivos

- Interpretar o modelo relacional da aplicação.
- Diferenciar migration, Model e Seeder.
- Usar tipos, restrições e chaves estrangeiras.
- Fazer consultas básicas com Eloquent.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–15 min | Modelagem das entidades no quadro. |
| 15–35 min | Leitura das migrations reais. |
| 35–50 min | Model, `$fillable`, casts e relações. |
| 50–75 min | Laboratório com Tinker. |
| 75–90 min | Desafio de migration. |

### Conceitos centrais

| Conceito | Pergunta respondida |
|---|---|
| Migration | Como a estrutura do banco é criada ou alterada? |
| Model | Como o PHP representa e manipula registros? |
| Seeder | Quais dados iniciais serão inseridos? |
| Relacionamento | Como registros de tabelas diferentes se conectam? |
| Restrição | Que estado inválido o banco deve impedir? |

### Demonstração no Tinker

```powershell
php artisan tinker
```

```php
use App\Models\Candidato;
use App\Models\Eleitor;
use App\Models\Voto;

Candidato::orderBy('numero')->get();
Candidato::where('ativo', true)->count();
Eleitor::whereNull('votou_em')->get();
Voto::where('tipo', 'branco')->count();
```

### Atividade prática

Criar a entidade `LocalVotacao`:

- migration com nome, sala, ativo e timestamps;
- Model com `$fillable` e cast booleano;
- Seeder com dois locais;
- consultas no Tinker.

### Perguntas formativas

- Por que `numero` possui `unique()`?
- Por que `foto` aceita `null`?
- Por que `candidato_id` aceita `null` em votos?
- O que aconteceria se votos possuíssem `eleitor_id`?
- Qual diferença entre `first()` e `get()`?

---

## Aula 4 — Administração, validação e CRUD

### Objetivos

- Relacionar as ações CRUD aos métodos de um Resource Controller.
- Validar dados no servidor.
- Entender autenticação e middleware.
- Manipular upload e exclusão com segurança.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–15 min | Mapeamento do CRUD. |
| 15–35 min | Leitura do `CandidatoController`. |
| 35–50 min | Validação e mensagens de erro. |
| 50–65 min | Login, sessão e middleware. |
| 65–85 min | Prática de CRUD em duplas. |
| 85–90 min | Autoavaliação. |

### Mapa do Resource Controller

| Ação | Verbo/rota | Método |
|---|---|---|
| Listar | GET `/candidatos` | `index` |
| Formulário novo | GET `/candidatos/create` | `create` |
| Salvar | POST `/candidatos` | `store` |
| Formulário edição | GET `/candidatos/{id}/edit` | `edit` |
| Atualizar | PUT `/candidatos/{id}` | `update` |
| Excluir | DELETE `/candidatos/{id}` | `destroy` |

### Demonstração de validação

Faça três tentativas:

1. nome vazio;
2. número com um dígito;
3. número igual ao de outro candidato.

Antes de enviar, peça que os alunos prevejam qual camada rejeitará cada tentativa: navegador, Laravel ou MySQL.

### Discussão importante

O método `destroy()` não exclui candidato que já recebeu votos. Mostre que “excluir” é uma decisão do domínio, não somente uma operação SQL.

### Evidência de aprendizagem

Cada dupla explica uma operação CRUD citando View, rota, Controller, validação, Model e resposta.

---

## Aula 5 — Blade, CSS, JavaScript e acessibilidade

### Objetivos

- Usar layouts, seções, componentes e diretivas Blade.
- Construir formulários protegidos por CSRF.
- Explicar a divisão entre comportamento do servidor e do navegador.
- Avaliar responsividade e acessibilidade.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–20 min | Layouts e herança Blade. |
| 20–35 min | Formulários, `old()` e erros. |
| 35–55 min | Estado visual da urna em JavaScript. |
| 55–70 min | CSS responsivo e acessibilidade. |
| 70–90 min | Melhoria de uma tela. |

### Demonstração Blade

Compare `layouts/app.blade.php` com uma View que usa `@extends`. Mostre como uma alteração no cabeçalho aparece em várias páginas.

Pergunte por que o projeto usa:

- `{{ }}` para saída escapada;
- `@csrf` em formulários;
- `@method('PUT')` e `@method('DELETE')`;
- `old()` após falha de validação;
- `@include` para mensagens reutilizáveis.

### Estratégia para ensinar o JavaScript da urna

Não comece pelo código. Desenhe os estados:

```text
Inicial → Um dígito → Dois dígitos → Candidato/Nulo → Confirmado
   ↘ Branco ────────────────────────────────────────↗
   ←──────────────── Corrige ───────────────────────
```

Depois associe variáveis, elementos e eventos a cada transição.

### Checklist de avaliação da interface

- O campo possui rótulo?
- É possível usar teclado?
- A mudança de candidato é anunciada?
- O contraste é suficiente?
- A tela funciona em largura pequena?
- O erro diz como corrigir o problema?

---

## Aula 6 — Fluxo da votação, sessão e transação

### Objetivos

- Explicar o ciclo de vida da sessão do eleitor.
- Diferenciar votos válidos, brancos e nulos.
- Compreender atomicidade e concorrência.
- Relacionar a modelagem ao sigilo do voto.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–15 min | Desenho do fluxo da votação. |
| 15–30 min | Identificação e sessão. |
| 30–45 min | Consulta JSON do candidato. |
| 45–65 min | Classificação e registro do voto. |
| 65–75 min | Transação e `lockForUpdate()`. |
| 75–90 min | Investigação prática. |

### Fluxo para acompanhar no código

1. `identificacao()` exibe o formulário.
2. `acessar()` valida o código e cria sessão.
3. `votacao()` verifica eleitor e situação da eleição.
4. `consultarCandidato()` responde JSON.
5. `confirmar()` classifica e grava o voto.
6. a sessão do eleitor é removida.
7. `concluida()` exibe a confirmação.

### Analogia da transação

Uma transferência bancária precisa retirar de uma conta e adicionar em outra. Se apenas uma etapa ocorrer, o sistema fica inconsistente. Na urna, criar o voto e marcar o eleitor também precisam ocorrer juntos.

### Experimento

Use as ferramentas do navegador para acompanhar:

- requisição de consulta do candidato;
- resposta JSON;
- POST de confirmação;
- redirecionamento final.

Depois confira no banco que o voto existe, o eleitor está marcado e não há ligação entre eles.

### Avaliação formativa

O aluno deve explicar:

- por que a interface não decide definitivamente se o voto é válido;
- por que o servidor consulta o candidato novamente;
- por que a sessão é removida;
- por que o eleitor é bloqueado durante a transação.

---

## Aula 7 — Apuração, matemática e segurança

### Objetivos

- Construir agregações e percentuais.
- Diferenciar votos totais e válidos.
- Reconhecer camadas de segurança.
- Discutir limites éticos do projeto.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–20 min | Consultas e contagens. |
| 20–35 min | Percentuais e casos de divisão por zero. |
| 35–50 min | Dashboard e visualização. |
| 50–70 min | Auditoria de segurança em grupos. |
| 70–85 min | Discussão ética. |
| 85–90 min | Síntese. |

### Atividade matemática

Forneça este cenário:

- 30 eleitores;
- 24 votos registrados;
- 20 votos válidos;
- candidato A: 12 votos;
- candidato B: 8 votos;
- 2 brancos e 2 nulos.

Respostas esperadas:

- participação: 24 ÷ 30 = 80%;
- candidato A: 12 ÷ 20 = 60% dos válidos;
- candidato B: 8 ÷ 20 = 40% dos válidos;
- brancos e nulos não entram no percentual dos candidatos.

### Auditoria em grupos

Cada grupo examina uma dimensão:

- autenticação e sessão;
- CSRF e validação;
- constraints, transação e concorrência;
- privacidade e exposição dos resultados.

Para cada dimensão, registre:

1. proteção presente;
2. ataque ou erro evitado;
3. limitação restante;
4. melhoria proposta.

### Limite do projeto

Reforce que esta é uma simulação educacional. Sistemas eleitorais oficiais exigem requisitos legais, auditoria independente, criptografia, controle de hardware, cadeia de custódia e processos que estão fora do escopo.

---

## Aula 8 — Testes, depuração e apresentação final

### Objetivos

- Ler e escrever testes de feature.
- Organizar testes em Arrange, Act e Assert.
- Diagnosticar erros com ferramentas do Laravel.
- Apresentar decisões técnicas com evidências.

### Sequência sugerida

| Tempo | Atividade |
|---|---|
| 0–20 min | Leitura coletiva de um teste. |
| 20–40 min | Escrita de um novo cenário. |
| 40–55 min | Rodada de depuração. |
| 55–70 min | Checklist e preparação. |
| 70–90 min | Apresentações curtas dos grupos. |

### Cenários de teste disponíveis

- página inicial responde;
- voto válido cria registro e marca eleitor;
- número inexistente vira voto nulo;
- eleitor pode votar em branco;
- código utilizado não acessa novamente;
- painel exige autenticação;
- páginas administrativas renderizam;
- administrador edita eleitor.

### Prática de teste

Escolha um cenário ainda não coberto, por exemplo:

- eleição encerrada bloqueia acesso;
- candidato inativo produz voto nulo;
- número duplicado é rejeitado;
- candidato com voto não pode ser excluído.

O teste deve conter um nome que descreva a regra, preparação mínima e pelo menos uma asserção relevante.

### Rodada de depuração

Entregue erros preparados ou use exemplos reais:

- rota com nome incorreto;
- coluna escrita de forma diferente da migration;
- `$fillable` sem um campo necessário;
- banco configurado com senha vazia;
- View referenciando variável ausente.

O aluno deve registrar hipótese, evidência e correção — não apenas “funcionou”.

---

## Projeto final e diferenciação

### Escolhas de extensão

**Nível inicial — Cargos**

- cadastrar cargo;
- associar candidatos;
- mostrar cargo na urna;
- testar cadastro.

**Nível intermediário — Turmas**

- cadastrar turmas;
- associar eleitores;
- filtrar participação;
- testar relacionamento.

**Nível avançado — Várias eleições**

- preservar históricos;
- associar candidatos, eleitores e votos à eleição;
- controlar eleições abertas;
- migrar dados existentes.

### Apoio para quem precisa

- forneça migrations parcialmente preenchidas;
- use diagramas de fluxo com lacunas;
- trabalhe com programação em pares;
- limite a tarefa a uma camada por vez;
- ofereça testes prontos como especificação.

### Ampliação para quem avança rápido

- criar Form Requests;
- usar Policies para autorização;
- adicionar factories;
- testar upload de arquivos;
- medir consultas e discutir N+1;
- criar exportação CSV da apuração.

## Rubrica de avaliação

| Critério | Peso | Excelente | Em desenvolvimento |
|---|---:|---|---|
| Modelagem | 20% | tipos, relações e constraints coerentes | estrutura incompleta ou inconsistente |
| MVC | 20% | responsabilidades claras | regras espalhadas em Views ou rotas |
| Funcionalidade | 25% | fluxo completo e casos alternativos | apenas caminho principal funciona |
| Segurança | 15% | validação, sessão e regras protegidas | confia apenas na interface |
| Testes | 10% | cenários relevantes e legíveis | testes ausentes ou superficiais |
| Comunicação | 10% | justifica decisões com evidências | apenas demonstra sem explicar |

## Perguntas para apresentação dos grupos

1. Qual problema sua alteração resolve?
2. Que migration ou mudança no banco foi necessária?
3. Qual Model representa os dados?
4. Como a requisição percorre rota e Controller?
5. Que regra é validada no servidor?
6. Como vocês verificaram o comportamento?
7. Que risco ou limitação ainda existe?

## Resumo conceitual esperado

Ao concluir, o aluno deve conseguir afirmar:

- requisito descreve uma necessidade ou restrição;
- rota liga uma requisição a uma ação;
- Controller coordena o caso de uso;
- Model representa e consulta dados;
- migration versiona a estrutura do banco;
- Blade gera a interface no servidor;
- JavaScript cuida de interações locais;
- middleware filtra o acesso;
- validação rejeita entrada inválida;
- transação preserva consistência;
- teste automatizado verifica comportamento repetidamente.

<?php

namespace Tests\Feature;

use App\Models\Candidato;
use App\Models\ConfiguracaoEleicao;
use App\Models\Eleitor;
use App\Models\User;
use App\Models\Voto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrnaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ConfiguracaoEleicao::query()->create([
            'titulo' => 'Eleição de teste',
            'status' => ConfiguracaoEleicao::STATUS_ABERTA,
        ]);
    }

    public function test_pagina_inicial_e_exibida(): void
    {
        $this->get('/')->assertOk()->assertSee('Eleição de teste');
    }

    public function test_eleitor_registra_voto_valido_sem_ligacao_com_o_voto(): void
    {
        $eleitor = Eleitor::query()->create(['nome' => 'Maria', 'codigo_acesso' => '123456']);
        $candidato = Candidato::query()->create([
            'nome' => 'Candidato Teste', 'numero' => '12', 'partido' => 'PTA', 'ativo' => true,
        ]);

        $this->post(route('urna.acessar'), ['codigo_acesso' => '123456'])
            ->assertRedirect(route('urna.votacao'));

        $this->post(route('urna.confirmar'), ['tipo' => 'numero', 'numero' => '12'])
            ->assertRedirect(route('urna.concluida'));

        $this->assertDatabaseHas('votos', [
            'candidato_id' => $candidato->id,
            'tipo' => Voto::TIPO_VALIDO,
            'numero_digitado' => '12',
        ]);
        $this->assertNotNull($eleitor->fresh()->votou_em);
        $this->assertArrayNotHasKey('eleitor_id', (new Voto)->getFillable());
    }

    public function test_numero_inexistente_vira_voto_nulo(): void
    {
        Eleitor::query()->create(['nome' => 'João', 'codigo_acesso' => '222222']);

        $this->post(route('urna.acessar'), ['codigo_acesso' => '222222']);
        $this->post(route('urna.confirmar'), ['tipo' => 'numero', 'numero' => '99']);

        $this->assertDatabaseHas('votos', [
            'candidato_id' => null,
            'tipo' => Voto::TIPO_NULO,
            'numero_digitado' => '99',
        ]);
    }

    public function test_eleitor_pode_votar_em_branco(): void
    {
        Eleitor::query()->create(['nome' => 'José', 'codigo_acesso' => '333333']);

        $this->post(route('urna.acessar'), ['codigo_acesso' => '333333']);
        $this->post(route('urna.confirmar'), ['tipo' => 'branco']);

        $this->assertDatabaseHas('votos', ['tipo' => Voto::TIPO_BRANCO]);
    }

    public function test_codigo_ja_utilizado_nao_acessa_a_urna(): void
    {
        Eleitor::query()->create([
            'nome' => 'Eleitor que votou',
            'codigo_acesso' => '444444',
            'votou_em' => now(),
        ]);

        $this->from(route('urna.identificacao'))
            ->post(route('urna.acessar'), ['codigo_acesso' => '444444'])
            ->assertRedirect(route('urna.identificacao'))
            ->assertSessionHasErrors('codigo_acesso');
    }

    public function test_painel_exige_autenticacao(): void
    {
        $this->get(route('administracao.painel'))
            ->assertRedirect(route('administracao.entrar'));
    }

    public function test_administrador_visualiza_as_paginas_do_painel(): void
    {
        $administrador = User::factory()->create();

        $this->actingAs($administrador)->get(route('administracao.painel'))->assertOk();
        $this->actingAs($administrador)->get(route('administracao.candidatos.index'))->assertOk();
        $this->actingAs($administrador)->get(route('administracao.eleitores.index'))->assertOk();
        $this->actingAs($administrador)->get(route('administracao.resultados'))->assertOk();
    }

    public function test_administrador_edita_um_eleitor(): void
    {
        $administrador = User::factory()->create();
        $eleitor = Eleitor::query()->create(['nome' => 'Nome antigo', 'codigo_acesso' => '555555']);

        $this->actingAs($administrador)->put(
            route('administracao.eleitores.update', $eleitor),
            ['nome' => 'Nome atualizado', 'codigo_acesso' => '555555']
        )->assertRedirect(route('administracao.eleitores.index'));

        $this->assertDatabaseHas('eleitores', ['id' => $eleitor->id, 'nome' => 'Nome atualizado']);
    }
}

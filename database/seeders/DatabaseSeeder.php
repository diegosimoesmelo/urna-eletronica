<?php

namespace Database\Seeders;

use App\Models\Candidato;
use App\Models\ConfiguracaoEleicao;
use App\Models\Eleitor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@urna.test'],
            ['name' => 'Administrador', 'password' => '12345678']
        );

        ConfiguracaoEleicao::query()->updateOrCreate(
            ['id' => 1],
            ['titulo' => 'Eleição da Turma', 'status' => ConfiguracaoEleicao::STATUS_ABERTA]
        );

        Candidato::query()->updateOrCreate(
            ['numero' => '12'],
            ['nome' => 'Ana Silva', 'partido' => 'Partido Aprender', 'ativo' => true]
        );
        Candidato::query()->updateOrCreate(
            ['numero' => '34'],
            ['nome' => 'Bruno Santos', 'partido' => 'Partido Inovar', 'ativo' => true]
        );
        Candidato::query()->updateOrCreate(
            ['numero' => '56'],
            ['nome' => 'Carla Oliveira', 'partido' => 'Partido Colaborar', 'ativo' => true]
        );

        foreach ([
            ['nome' => 'Eleitor 1', 'codigo_acesso' => '100001'],
            ['nome' => 'Eleitor 2', 'codigo_acesso' => '100002'],
            ['nome' => 'Eleitor 3', 'codigo_acesso' => '100003'],
            ['nome' => 'Eleitor 4', 'codigo_acesso' => '100004'],
            ['nome' => 'Eleitor 5', 'codigo_acesso' => '100005'],
        ] as $eleitor) {
            Eleitor::query()->firstOrCreate(['codigo_acesso' => $eleitor['codigo_acesso']], $eleitor);
        }
    }
}

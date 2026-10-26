<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoEleicao extends Model
{
    public const STATUS_CONFIGURACAO = 'configuracao';

    public const STATUS_ABERTA = 'aberta';

    public const STATUS_ENCERRADA = 'encerrada';

    protected $table = 'configuracoes_eleicao';

    protected $fillable = ['titulo', 'status'];

    public static function atual(): self
    {
        return self::query()->firstOrCreate(
            ['id' => 1],
            ['titulo' => 'Eleição da Turma', 'status' => self::STATUS_CONFIGURACAO]
        );
    }
}

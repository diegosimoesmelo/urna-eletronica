<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voto extends Model
{
    use HasFactory;

    public const TIPO_VALIDO = 'valido';

    public const TIPO_BRANCO = 'branco';

    public const TIPO_NULO = 'nulo';

    protected $table = 'votos';

    protected $fillable = ['candidato_id', 'tipo', 'numero_digitado'];

    public function candidato(): BelongsTo
    {
        return $this->belongsTo(Candidato::class, 'candidato_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleitor extends Model
{
    use HasFactory;

    protected $table = 'eleitores';

    protected $fillable = ['nome', 'codigo_acesso', 'votou_em'];

    protected function casts(): array
    {
        return ['votou_em' => 'datetime'];
    }

    public function jaVotou(): bool
    {
        return $this->votou_em !== null;
    }
}

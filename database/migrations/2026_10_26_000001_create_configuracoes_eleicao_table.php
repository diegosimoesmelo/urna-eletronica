<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes_eleicao', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->default('Eleição da Turma');
            $table->enum('status', ['configuracao', 'aberta', 'encerrada'])->default('configuracao');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_eleicao');
    }
};

<?php

use App\Http\Controllers\Administracao\CandidatoController;
use App\Http\Controllers\Administracao\EleicaoController;
use App\Http\Controllers\Administracao\EleitorController;
use App\Http\Controllers\Administracao\PainelController;
use App\Http\Controllers\Administracao\ResultadoController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\UrnaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InicioController::class, 'index'])->name('inicio');

Route::prefix('urna')->name('urna.')->group(function () {
    Route::get('/', [UrnaController::class, 'identificacao'])->name('identificacao');
    Route::post('/acessar', [UrnaController::class, 'acessar'])->name('acessar');
    Route::get('/votacao', [UrnaController::class, 'votacao'])->name('votacao');
    Route::get('/candidato/{numero}', [UrnaController::class, 'consultarCandidato'])->name('candidato');
    Route::post('/confirmar', [UrnaController::class, 'confirmar'])->name('confirmar');
    Route::post('/cancelar', [UrnaController::class, 'cancelar'])->name('cancelar');
    Route::get('/concluida', [UrnaController::class, 'concluida'])->name('concluida');
});

Route::prefix('administracao')->name('administracao.')->group(function () {
    Route::get('/entrar', [AutenticacaoController::class, 'formulario'])->name('entrar');
    Route::post('/entrar', [AutenticacaoController::class, 'entrar'])->name('autenticar');

    Route::middleware('auth')->group(function () {
        Route::get('/', [PainelController::class, 'index'])->name('painel');
        Route::put('/eleicao', [EleicaoController::class, 'atualizar'])->name('eleicao.atualizar');
        Route::resource('candidatos', CandidatoController::class)->except('show');
        Route::resource('eleitores', EleitorController::class)
            ->parameters(['eleitores' => 'eleitor'])
            ->except('show');
        Route::get('/resultados', [ResultadoController::class, 'index'])->name('resultados');
        Route::post('/sair', [AutenticacaoController::class, 'sair'])->name('sair');
    });
});

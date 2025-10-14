<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\ConteudoController;


//Crud basico
Route::get("/conteudos", [ConteudoController::class,"index"])->name("Listar conteúdos");
Route::post("/conteudos", [ConteudoController::class,"store"])->name("Criar conteúdo");
Route::get("/conteudos/{id}", [ConteudoController::class,"show"])->name("Listar apenas um conteúdo");
Route::put("/conteudos/{id}", [ConteudoController::class,"update"])->name("Atualiza apenas um conteúdo");
Route::delete("/conteudos/{id}", [ConteudoController::class,"destroy"])->name("Deleta um conteúdo");

// rota para aprovar e reprovar
Route::post("/conteudos/{conteudo}/aprovar", [ConteudoController::class,"aprovar"])->name("Aprovar conteúdo");
Route::post("/conteudos/{conteudo}/reprovar", [ConteudoController::class,"reprovar"])->name("Reprovar conteúdo");






// Route::post('/user', [TokenController::class, 'index']); //200 ou 401




//Route::group(['middleware' => ['JWTToken']], function () {
    // Route::get('/agenda', [AgendaController::class, 'index']);  //200

// });




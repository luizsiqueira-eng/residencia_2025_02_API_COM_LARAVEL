<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use Exception;
use App\Http\Requests\ConteudoRequest;


class ConteudoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(response()->json(Conteudo::all(), 200));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ConteudoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ConteudoRequest $request)
    {
        $conteudo = Conteudo::create($request->validated());
        return response()->json($conteudo, 201); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Conteudo $conteudo)
    {
        return response()->json($conteudo, 200);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ConteudoRequest $request, Conteudo $conteudo)
    {
        $data = $request->validated();

        try {
        
            if (isset($data['status'])) {
                if ($data['status'] === 'aprovado') {
                    $conteudo->aprovar();
                } elseif ($data['status'] === 'reprovado') {
                    $conteudo->reprovar($data['motivo_reprovacao']);
                }
            }

            
            if (isset($data['conteudo'])) {
                $conteudo->statusEscritoAposEditarConteudoReprovado();
                $conteudo->conteudo = $data['conteudo'];
                $conteudo->save();
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422); 
        }

        return response()->json($conteudo->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Conteudo $conteudo)
    {
        $conteudo->delete();
        return response()->json(null, 204);
    }
}

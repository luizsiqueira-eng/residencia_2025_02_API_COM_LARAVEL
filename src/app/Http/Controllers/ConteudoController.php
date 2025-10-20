<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use App\Models\ConteudoLog;
use Exception;
use App\Http\Requests\ConteudoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ConteudoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Conteudo::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('papel')) {
            $query->where('papel', $request->input('papel'));
        }

        $perPage = $request->input('per_page', 10);
        $conteudos = $query->paginate($perPage);

        return response()->json($conteudos, 200);
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
        ConteudoLog::create([
            'conteudo_id' => $conteudo->id,
            'acao' => 'criar',
        ]);
        return response()->json($conteudo, 201); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Conteudo $conteudo, $id)
    {
        $conteudo = Conteudo::findOrFail($id);
        
        return response()->json($conteudo, 200);
    }

    /**
     * Aprova um conteúdo específico.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\JsonResponse
     */
    public function aprovar(Conteudo $conteudo): JsonResponse
    {
        try {
            $conteudo->aprovar();
            ConteudoLog::create([
                'conteudo_id' => $conteudo->id,
                'acao' => 'aprovar',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($conteudo->fresh(), 200);
    }

    /**
     * Reprova um conteúdo específico.
     *
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\JsonResponse
     */
    public function reprovar(Request $request, Conteudo $conteudo): JsonResponse
    {
        try {
            $validated = $request->validate(['motivo_reprovacao' => 'required|string']);
            $conteudo->reprovar($validated['motivo_reprovacao']);
            ConteudoLog::create([
                'conteudo_id' => $conteudo->id,
                'acao' => 'reprovar',
                'detalhes' => 'Motivo: ' . $validated['motivo_reprovacao'],
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($conteudo->fresh(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conteudo  $conteudo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ConteudoRequest $request, Conteudo $conteudo): JsonResponse
    {
        $data = $request->validated();

        try {

            if (isset($data['conteudo'])) {
                $conteudo->statusEscritoAposEditarConteudoReprovado();
            }

            $conteudo->update($data);

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
        
        if ($conteudo->status === \App\Enums\ConteudoStatusEnum::APROVADO) {
            return response()->json(['error' => 'Conteúdos aprovados não podem ser excluídos.'], 422);
        }
        
        $conteudoId = $conteudo->id;
        $conteudo->delete();

         ConteudoLog::create([
            'conteudo_id' => $conteudoId,
            'acao' => 'deletar',
        ]);

        

        return response()->json(null, 204);
    }
}

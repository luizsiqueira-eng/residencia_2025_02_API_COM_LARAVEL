<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Conteudo;
use App\Enums\ConteudoStatusEnum;

class ConteudoControllerTest extends TestCase
{
    use RefreshDatabase; 

    /** @test */
    public function it_can_list_all_conteudos()
    {
        
        Conteudo::factory()->count(3)->create();

        $response = $this->getJson('/api/conteudos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_conteudo()
    {
        $data = [
            'papel' => 'redator',
            'conteudo' => 'Este é um conteúdo com mais de vinte caracteres para passar na validação.'
        ];

        $response = $this->postJson('/api/conteudos', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas('conteudos', $data);
    }

    /** @test */
    public function it_fails_to_create_a_conteudo_with_invalid_data()
    {
        $data = [
            'papel' => 'redator',
            'conteudo' => 'conteudo curto' 
        ];

        $response = $this->postJson('/api/conteudos', $data);

        $response->assertStatus(422) 
                 ->assertJsonValidationErrors('conteudo');
    }

    /** @test */
    public function it_can_show_a_specific_conteudo()
    {
        $conteudo = Conteudo::factory()->create();

        $response = $this->getJson("/api/conteudos/{$conteudo->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $conteudo->id]);
    }

    /** @test */
    /** @test */
    public function it_can_approve_a_conteudo()
    {
        $conteudo = Conteudo::factory()->create(['status' => ConteudoStatusEnum::ESCRITO]);

        // CORREÇÃO: Chamar a rota POST /aprovar, em vez de PUT
        $response = $this->postJson("/api/conteudos/{$conteudo->id}/aprovar");

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => ConteudoStatusEnum::APROVADO]);

        $this->assertDatabaseHas('conteudos', [
            'id' => $conteudo->id,
            'status' => ConteudoStatusEnum::APROVADO
        ]);
    }

    /** @test */
    public function it_can_reprove_a_conteudo()
    {
        $conteudo = Conteudo::factory()->create(['status' => ConteudoStatusEnum::ESCRITO]);
        $motivo = 'Este é o motivo da reprovação com mais de 20 caracteres.';

        // CORREÇÃO: Chamar a rota POST /reprovar, em vez de PUT
        $response = $this->postJson("/api/conteudos/{$conteudo->id}/reprovar", [
            'motivo_reprovacao' => $motivo
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'status' => ConteudoStatusEnum::REPROVADO,
                     'motivo_reprovacao' => $motivo
                 ]);
    }

    /** @test */
    public function it_can_delete_a_conteudo()
    {
        $conteudo = Conteudo::factory()->create();

        $response = $this->deleteJson("/api/conteudos/{$conteudo->id}");

        $response->assertStatus(204);

        
        $this->assertDatabaseMissing('conteudos', ['id' => $conteudo->id]);
    }
}
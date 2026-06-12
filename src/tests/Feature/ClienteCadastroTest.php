<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteCadastroTest extends TestCase
{
    use RefreshDatabase;

    /** CAD-01 */
    public function test_listagem_de_clientes_carrega_e_exibe_registros()
    {
        $cliente = Cliente::factory()->create(['nome' => 'Maria Silva']);

        $this->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('Maria Silva');
    }

    /** CAD-01 */
    public function test_cria_cliente_via_formulario()
    {
        $response = $this->post(route('clientes.store'), [
            'nome' => 'Maria Silva',
            'cpf' => '12345678909',
            'email' => 'maria@example.com',
            'telefone' => '81999990000',
        ]);

        $response->assertRedirect(route('clientes.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('clientes', ['cpf' => '12345678909']);
    }

    /** CAD-02 */
    public function test_aceita_cpf_com_mascara_e_armazena_so_digitos()
    {
        $this->post(route('clientes.store'), [
            'nome' => 'João Souza',
            'cpf' => '123.456.789-09',
        ])->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('clientes', ['cpf' => '12345678909']);
    }

    /** CAD-02 */
    public function test_rejeita_cpf_duplicado()
    {
        Cliente::factory()->create(['cpf' => '12345678909']);

        $this->from(route('clientes.create'))
            ->post(route('clientes.store'), ['nome' => 'Outro', 'cpf' => '12345678909'])
            ->assertRedirect(route('clientes.create'))
            ->assertSessionHasErrors('cpf');

        $this->assertSame(1, Cliente::count());
    }

    /** CAD-02 */
    public function test_rejeita_cliente_sem_nome_ou_sem_cpf()
    {
        $this->post(route('clientes.store'), [])
            ->assertSessionHasErrors(['nome', 'cpf']);
    }

    /** CAD-01, CAD-02 */
    public function test_atualiza_cliente_mantendo_proprio_cpf()
    {
        $cliente = Cliente::factory()->create(['cpf' => '12345678909']);

        $this->put(route('clientes.update', $cliente), [
            'nome' => 'Nome Atualizado',
            'cpf' => '12345678909',
        ])->assertRedirect(route('clientes.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('clientes', ['id' => $cliente->id, 'nome' => 'Nome Atualizado']);
    }

    /** CAD-01 */
    public function test_exclui_cliente_sem_veiculos()
    {
        $cliente = Cliente::factory()->create();

        $this->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    /** CAD-07 */
    public function test_bloqueia_exclusao_de_cliente_com_veiculos()
    {
        $cliente = Cliente::factory()->create();
        Veiculo::factory()->create(['cliente_id' => $cliente->id]);

        $this->delete(route('clientes.destroy', $cliente))
            ->assertRedirect(route('clientes.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('clientes', ['id' => $cliente->id]);
    }

    /** CAD-05 */
    public function test_pagina_do_cliente_exibe_seus_veiculos()
    {
        $cliente = Cliente::factory()->create();
        Veiculo::factory()->create(['cliente_id' => $cliente->id, 'placa' => 'KKK1A11']);
        $outro = Veiculo::factory()->create(['placa' => 'WWW2B22']);

        $this->get(route('clientes.show', $cliente))
            ->assertOk()
            ->assertSee('KKK1A11')
            ->assertDontSee('WWW2B22');
    }
}

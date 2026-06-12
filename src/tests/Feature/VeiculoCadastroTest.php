<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VeiculoCadastroTest extends TestCase
{
    use RefreshDatabase;

    /** CAD-03 */
    public function test_cria_veiculo_para_cliente_existente()
    {
        $cliente = Cliente::factory()->create();

        $this->post(route('veiculos.store'), [
            'cliente_id' => $cliente->id,
            'placa' => 'ABC1234',
            'marca' => 'Fiat',
            'modelo' => 'Argo',
            'cor' => 'prata',
        ])->assertRedirect(route('veiculos.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('veiculos', ['placa' => 'ABC1234', 'cliente_id' => $cliente->id]);
    }

    /** CAD-04 */
    public function test_aceita_placa_mercosul_e_normaliza_minusculas()
    {
        $cliente = Cliente::factory()->create();

        $this->post(route('veiculos.store'), [
            'cliente_id' => $cliente->id,
            'placa' => 'abc1d23',
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('veiculos', ['placa' => 'ABC1D23']);
    }

    /** CAD-04 */
    public function test_rejeita_placa_em_formato_invalido()
    {
        $cliente = Cliente::factory()->create();

        $this->post(route('veiculos.store'), [
            'cliente_id' => $cliente->id,
            'placa' => '1234ABC',
            'marca' => 'Fiat',
            'modelo' => 'Argo',
        ])->assertSessionHasErrors('placa');

        $this->assertSame(0, Veiculo::count());
    }

    /** CAD-04 */
    public function test_rejeita_placa_duplicada()
    {
        $cliente = Cliente::factory()->create();
        Veiculo::factory()->create(['placa' => 'ABC1234']);

        $this->post(route('veiculos.store'), [
            'cliente_id' => $cliente->id,
            'placa' => 'ABC1234',
            'marca' => 'Fiat',
            'modelo' => 'Argo',
        ])->assertSessionHasErrors('placa');
    }

    /** CAD-03 */
    public function test_rejeita_veiculo_com_cliente_inexistente()
    {
        $this->post(route('veiculos.store'), [
            'cliente_id' => 999,
            'placa' => 'ABC1234',
            'marca' => 'Fiat',
            'modelo' => 'Argo',
        ])->assertSessionHasErrors('cliente_id');
    }

    /** CAD-03 */
    public function test_atualiza_veiculo()
    {
        $veiculo = Veiculo::factory()->create(['cor' => 'preto']);

        $this->put(route('veiculos.update', $veiculo), ['cor' => 'vermelho'])
            ->assertRedirect(route('veiculos.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('veiculos', ['id' => $veiculo->id, 'cor' => 'vermelho']);
    }

    /** CAD-03 */
    public function test_exclui_veiculo()
    {
        $veiculo = Veiculo::factory()->create();

        $this->delete(route('veiculos.destroy', $veiculo))
            ->assertRedirect(route('veiculos.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('veiculos', ['id' => $veiculo->id]);
    }

    /** CAD-03 */
    public function test_listagem_exibe_nome_do_cliente()
    {
        $cliente = Cliente::factory()->create(['nome' => 'Dono do Carro']);
        Veiculo::factory()->create(['cliente_id' => $cliente->id]);

        $this->get(route('veiculos.index'))
            ->assertOk()
            ->assertSee('Dono do Carro');
    }

    /** CAD-06 */
    public function test_busca_por_placa_filtra_listagem_case_insensitive()
    {
        Veiculo::factory()->create(['placa' => 'XYZ9A88']);
        Veiculo::factory()->create(['placa' => 'QQQ1B11']);

        $this->get(route('veiculos.index', ['placa' => 'xyz9a88']))
            ->assertOk()
            ->assertSee('XYZ9A88')
            ->assertDontSee('QQQ1B11');
    }

    /** CAD-06 */
    public function test_busca_por_placa_inexistente_mostra_lista_vazia()
    {
        Veiculo::factory()->create(['placa' => 'XYZ9A88']);

        $this->get(route('veiculos.index', ['placa' => 'ZZZ0Z00']))
            ->assertOk()
            ->assertSee('Nenhum veículo encontrado')
            ->assertDontSee('XYZ9A88');
    }
}

<?php

/**
 * @coversDefaultClass \App\Rma
 */
class RmaTest extends TestCase {

    /**
     * @coversNothing
     */
    public function testEstadoRmaRequerido()
    {
        $rma = factory(App\Rma::class)->make([
            'cliente_id' => null
        ]);
        $this->assertFalse($rma->isValid());
    }

    /**
     * @coversNothing
     */
    public function testClienteRequerido()
    {
        $rma = factory(App\Rma::class)->make([
            'cliente_id' => null
        ]);
        $this->assertFalse($rma->isValid());
    }

    /**
     * @coversNothing
     */
    public function testEmpleadoRequerido()
    {
        $rma = factory(App\Rma::class)->make([
            'empleado_id' => null
        ]);
        $this->assertFalse($rma->isValid());
    }

    /**
     * @coversNothing
     */
    public function testTiempoRmaRequerido()
    {
        $rma = factory(App\Rma::class)->make([
            'rma_tiempo_id' => null
        ]);
        $this->assertFalse($rma->isValid());
    }

    /**
     * @coversNothing
     */
    public function testSucursalRequerido()
    {
        $rma = factory(App\Rma::class)->make([
            'sucursal_id' => null
        ]);
        $this->assertFalse($rma->isValid());
    }

    /**
     * @coversNothing
     */
    public function testNotaCreditoNoRequerido()
    {
        $rma = factory(App\Rma::class, 'sinnotacredito')->make();
        $this->assertTrue($rma->isValid());
    }

    /**
     * @covers ::estadoRma
     */
    public function testEstadoRma()
    {
        $estado_rma = factory(App\EstadoRma::class)->create();
        $rma = factory(App\Rma::class)->create([
            'estado_rma_id' => $estado_rma->id
        ]);
        $this->assertInstanceOf('App\EstadoRma', $rma->estadoRma);
    }

    /**
     * @covers ::cliente
     */
    public function testCliente()
    {
        $cliente = factory(App\Cliente::class, 'full')->create();
        $rma = factory(App\Rma::class)->create([
            'cliente_id' => $cliente->id
        ]);
        $this->assertEquals(App\Cliente::find($cliente->id), $rma->cliente);
    }

    /**
     * @covers ::empleado
     */
    public function testEmpleado()
    {
        $empleado = factory(App\Empleado::class)->create();
        $rma = factory(App\Rma::class)->create([
            'empleado_id' => $empleado->id
        ]);
        $this->assertEquals(App\Empleado::find($empleado->id), $rma->empleado);
    }

    /**
     * @covers ::rmaTiempo
     */
    public function testRmaTiempo()
    {
        $tiempo_rma = factory(App\RmaTiempo::class)->create();
        $rma = factory(App\Rma::class)->create([
            'rma_tiempo_id' => $tiempo_rma->id
        ]);
        $this->assertEquals(App\RmaTiempo::find($tiempo_rma->id), $rma->rmaTiempo);
    }

    /**
     * @covers ::sucursal
     */
    public function testSucursal()
    {
        $sucursal = factory(App\Sucursal::class)->create();
        $rma = factory(App\Rma::class)->create([
            'sucursal_id' => $sucursal->id
        ]);
        $this->assertEquals(App\Sucursal::find($sucursal->id), $rma->sucursal);
    }

    /**
     * @covers ::notaCredito
     */
    public function testNotaCredito()
    {
        $this->markTestIncomplete('NotaCredito Class not implemented yet.');
        $nota_credito = factory(App\NotaCredito::class)->create();
        $rma = factory(App\NotaCredito::class)->create([
            'nota_credito_id' => $nota_credito->id
        ]);
        $this->assertEquals($rma->sucursal, $nota_credito);
    }
}

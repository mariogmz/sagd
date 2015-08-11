<?php

/**
 * @coversDefaultClass \App\Empleado
 */
class EmpleadoTest extends TestCase {


    /**
     * @coversNothing
     */
    public function testModeloEmpleadoExiste() {
        $empleado = new App\Empleado();
        $this->assertInstanceOf(App\Empleado::class, $empleado);
    }

    /**
     * @coversNothing
     * @group modelo_actualizable
     */
    public function testModeloEsActualizable() {
        $empleado = factory(App\Empleado::class)->create();
        $empleado->nombre = 'Dr. Cod';
        $this->assertTrue($empleado->isValid('update'));
        $this->assertTrue($empleado->save());
    }

    /**
     * @coversNothing
     */
    public function testModeloEmpleadosAsociadoTablaEmpleados() {
        $empleado = new App\Empleado();
        $this->assertAttributeEquals('empleados', 'table', $empleado);
    }

    /**
     * @coversNothing
     */
    public function testModeloEmpleadosTieneValoresNecesarios() {
        $empleado = factory(App\Empleado::class)->make([
            'nombre'   => '',
            'usuario'  => '',
            'password' => '',
            'activo'   => null,
        ]);
        $this->assertFalse($empleado->isValid());
    }

    /**
     * @coversNothing
     */
    public function testModeloEmpleadosTienePasswordDiferenteDeUsuario() {
        $empleado = factory(App\Empleado::class)->make([
            'usuario'  => 'prueba',
            'password' => 'prueba'
        ]);
        $this->assertFalse($empleado->isValid());
    }

    /**
     * @coversNothing
     */
    public function testCadaEmpleadoTieneUsuarioUnico() {
        $empleado1 = factory(App\Empleado::class)->create();
        $empleado2 = factory(App\Empleado::class)->make([
            'usuario' => $empleado1->usuario
        ]);
        $this->assertFalse($empleado2->isValid());
    }

    /**
     * @coversNothing
     */
    public function testEmpleadoEsValido() {
        $empleado = factory(App\Empleado::class)->make();
        $this->assertTrue($empleado->isValid());
    }

    /**
     * @covers ::logsAccesos
     * @group relaciones
     */
    public function testLogsAccesos() {
        $empleado = factory(App\Empleado::class)->create();
        $logs_accesos = factory(App\LogAcceso::class, 5)->create([
            'empleado_id' => $empleado->id
        ]);
        $logs_accesos_resultado = $empleado->logsAccesos;
        for ($i = 0; $i < 5; $i ++) {
            $this->assertEquals($logs_accesos[$i], $logs_accesos_resultado[$i]);
        }
    }

    /**
     * @covers ::datoContacto
     * @group relaciones
     */
    public function testDatoContacto() {
        $empleado = factory(App\Empleado::class)->create();
        $dato_contacto = factory(App\DatoContacto::class)->create([
            'empleado_id' => $empleado->id
        ]);
        $dato_contacto_resultado = $empleado->datoContacto;
        $this->assertEquals($dato_contacto->id, $dato_contacto_resultado->id);
    }

    /**
     * @covers ::sucursal
     * @group relaciones
     */
    public function testSucursal() {
        $sucursal = factory(App\Sucursal::class)->create();
        $empleado = factory(App\Empleado::class)->create([
            'sucursal_id' => $sucursal->id
        ]);
        $sucursal_resultado = $empleado->sucursal;
        $this->assertEquals($sucursal, $sucursal_resultado);
    }

    /**
     * @covers ::serviciosSoportes
     * @group relaciones
     */
    public function testServiciosSoportes() {
        $empleado = factory(App\Empleado::class)->create();
        $servicios_soportes = factory(App\ServicioSoporte::class, 5)->create([
            'empleado_id' => $empleado->id
        ]);
        $servicios_soportes_resultado = $empleado->serviciosSoportes;
        for ($i = 0; $i < 5; $i ++) {
            $this->assertEquals($servicios_soportes[$i]->id, $servicios_soportes_resultado[$i]->id);
        }
    }

    /**
     * @covers ::rmas
     * @group relaciones
     */
    public function testRmas() {
        $empleado = factory(App\Empleado::class)->create();
        factory(App\Rma::class)->create([
            'empleado_id' => $empleado->id
        ]);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $empleado->rmas);
        $this->assertInstanceOf(App\Rma::class, $empleado->rmas[0]);
        $this->assertCount(1, $empleado->rmas);
    }

    /**
     * @covers ::salidas
     * @group relaciones
     */
    public function testSalidas() {
        $empleado = factory(App\Empleado::class)->create();
        factory(App\Salida::class, 'full')->create(['empleado_id' => $empleado->id]);
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $empleado->salidas);
        $this->assertInstanceOf(App\Salida::class, $empleado->salidas[0]);
        $this->assertCount(1, $empleado->salidas);
    }

    /**
     * @covers ::entradas
     * @group relaciones
     */
    public function testEntradas() {
        $empleado = factory(App\Empleado::class)->create();
        $entrada = factory(App\Entrada::class, 'full')->create(['empleado_id' => $empleado->id]);
        $entradas = $empleado->entradas;
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $entradas);
        $this->assertInstanceOf(App\Entrada::class, $entradas[0]);
        $this->assertCount(1, $entradas);
    }

    /**
     * @covers ::transferenciasOrigen
     * @group relaciones
     */
    public function testTransferenciasOrigen() {
        $empleado = factory(App\Empleado::class)->create();
        $transferencia = factory(App\Transferencia::class, 'full')->create(['empleado_origen_id' => $empleado->id]);
        $transferencias = $empleado->transferenciasOrigen;
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $transferencias);
        $this->assertInstanceOf(App\Transferencia::class, $transferencias[0]);
        $this->assertCount(1, $transferencias);
    }

    /**
     * @covers ::transferenciasDestino
     * @group relaciones
     */
    public function testTransferenciasDestino() {
        $empleado = factory(App\Empleado::class)->create();
        $transferencia = factory(App\Transferencia::class, 'full')->create(['empleado_destino_id' => $empleado->id]);
        $transferencias = $empleado->transferenciasDestino;
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $transferencias);
        $this->assertInstanceOf(App\Transferencia::class, $transferencias[0]);
        $this->assertCount(1, $transferencias);
    }

    /**
     * @covers ::transferenciasRevision
     * @group relaciones
     */
    public function testTransferenciasRevision() {
        $empleado = factory(App\Empleado::class)->create();
        $transferencia = factory(App\Transferencia::class, 'full')->create(['empleado_revision_id' => $empleado->id]);
        $transferencias = $empleado->transferenciasRevision;
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $transferencias);
        $this->assertInstanceOf(App\Transferencia::class, $transferencias[0]);
        $this->assertCount(1, $transferencias);
    }

    /**
     * @covers ::apartados
     * @group relaciones
     */
    public function testApartados() {
        $empleado = factory(App\Empleado::class)->create();
        $apartado = factory(App\Apartado::class, 'full')->create([
            'empleado_apartado_id' => $empleado->id]);
        $apartados = $empleado->apartados;
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $apartados);
        $this->assertInstanceOf(App\Apartado::class, $apartados[0]);
        $this->assertCount(1, $apartados);
    }

    /**
     * @covers ::desapartados
     * @group relaciones
     */
    public function testDesapartados() {
        $empleado = factory(App\Empleado::class)->create();
        factory(App\Apartado::class, 'full')->create([
            'empleado_desapartado_id' => $empleado->id]);
        $apartados = $empleado->desapartados;
        $this->assertInstanceOf(Illuminate\Database\Eloquent\Collection::class, $apartados);
        $this->assertInstanceOf(App\Apartado::class, $apartados[0]);
        $this->assertCount(1, $apartados);
    }

    /**
     * @covers ::cortes
     * @group relaciones
     */
    public function testCortes() {
        $empleado = factory(App\Empleado::class)->create();
        factory(App\Corte::class)->create([
            'empleado_id' => $empleado->id
        ]);
        $cortes = $empleado->cortes;
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $cortes);
        $this->assertInstanceOf(App\Corte::class, $cortes[0]);
        $this->assertCount(1, $cortes);
    }

    /**
     * @covers ::ventasMovimientos
     * @group relaciones
     */
    public function testVentasMovimientos() {
        $parent = factory(App\Empleado::class)->create();
        factory(App\VentaMovimiento::class)->create([
            'empleado_id' => $parent->id
        ]);
        $children = $parent->ventasMovimientos;
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $children);
        $this->assertInstanceOf('App\VentaMovimiento', $children[0]);
        $this->assertCount(1, $children);
    }

}

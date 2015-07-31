<?php

/**
 * @coversDefaultClass \App\RmaTiempo
 */
class RmaTiempoTest extends TestCase {

    /**
     * @coversNothing
     * @group modelo_actualizable
     */
    public function testModeloEsActualizable()
    {
        $rmat = factory(App\RmaTiempo::class)->create();
        $rmat->nombre = 'Tony Stark' .  rand(1, 10000);
        $this->assertTrue($rmat->isValid('update'));
        $this->assertTrue($rmat->save());
    }

    /**
     * @coversNothing
     */
    public function testNombreEsRequerido()
    {
        $rmatiempo = factory(App\RmaTiempo::class)->make([
            'nombre' => ''
        ]);
        $this->assertFalse($rmatiempo->isValid());
    }

    /**
     * @coversNothing
     */
    public function testNombreEsMaximo45Caracteres()
    {
        $rmatiempo = factory(App\RmaTiempo::class, 'nombrelargo')->make();
        $this->assertFalse($rmatiempo->isValid());
    }

    /**
     * @coversNothing
     */
    public function testNombreEsUnico()
    {
        $rmatiempo1 = factory(App\RmaTiempo::class)->create();
        $rmatiempo2 = factory(App\RmaTiempo::class)->make([
            'nombre' => $rmatiempo1->nombre
        ]);
        $this->assertFalse($rmatiempo2->isValid());
    }

    /**
     * @covers ::rmas
     */
    public function testRmas()
    {
        $rma_tiempo = factory(App\RmaTiempo::class)->create();
        $rmas = factory(App\Rma::class, 5)->create([
            'rma_tiempo_id' => $rma_tiempo->id
        ]);
        $rmas_resultado = $rma_tiempo->rmas;
        foreach ($rmas_resultado as $rr)
        {
            $this->assertInstanceOf('App\Rma', $rr);
        }
    }

}

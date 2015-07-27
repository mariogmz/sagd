<?php


class MarcaTest extends TestCase
{
    protected $marca;

    /**
     * @covers Unidad::()
     */
    public function testModeloEsValido()
    {
        $marca = factory(App\Marca::class)->make();
        $this->assertTrue($marca->isValid());
        $this->assertTrue($marca->save());
    }

    /**
    * @covers Marca::()
    */
    public function testClaveVaciaNoEsValido() {
        $marca = factory(App\Marca::class)->make(['clave' => '']);
        $this->assertFalse($marca->isValid());
        $this->assertFalse($marca->save());
    }

    /**
    * @covers Marca::()
    */
    public function testNombreVacioNoEsValido()
    {
        $marca = factory(App\Marca::class)->make(['nombre' => '']);
        $this->assertFalse($marca->isValid());
        $this->assertFalse($marca->save());
    }

    /**
    * @covers Marca::()
    */
    public function testClaveNoPuedeTenerMasDeTresDigitos()
    {
      $marca = factory(App\Marca::class)->make(['clave' => 'AAAA']);
      $this->assertFalse($marca->isValid());
      $this->assertFalse($marca->save());
    }

    /**
    * @covers Marca::()
    */
    public function testNombreNoPuedeTenerMasDe25Digitos()
    {
      $marca = factory(App\Marca::class, 'longname')->make();
      $this->assertFalse($marca->isValid());
      $this->assertFalse($marca->save());
    }

    /**
    * @covers Marca::()
    */
    public function testClaveDebeSerGuardadaEnMayusculas()
    {
        $marca = factory(App\Marca::class, 'lowercase')->make();
        $clave = strtoupper($marca->clave);
        $marca->save();
        $this->assertSame($clave, $marca->clave);
    }

    /**
     * @covers Marca::Productos()
     */
    public function testProductos()
    {
        $marca = factory(App\Marca::class)->create();
        $producto = factory(App\Producto::class)->create(['marca_id' => $marca->id]);
        $testProducto = $marca->productos[0];
        $this->assertInstanceOf(App\Producto::class, $testProducto);
    }
}

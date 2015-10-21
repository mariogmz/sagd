<?php

use Illuminate\Database\Seeder;

class ProductoTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
       DB::beginTransaction();
            try {
                $this->obtenerProductosDesdeLegacy();
            } catch (ErrorException $ex) {
                echo $ex->getMessage() . "\n";
                echo $ex->getFile() . "\n";
                echo $ex->getLine() . "\n";
                DB::rollBack();
            }
        DB::commit();
    }

    private function obtenerProductosDesdeLegacy() {
        $legacy = DB::connection('mysql_legacy');
        // Obtiene de legacy los campos normales con el formato para el sistema nuevo, no incluye foreign keys
        $productos_legacy = $legacy->select("
        SELECT
            activo,
            productos.Clave AS clave,
            productos.Descripcion AS descripcion,
            substring(productos.Descripcion,1,50) AS descripcion_corta,
            if(fechaentrada IS NULL, current_timestamp, date_format(fechaentrada,'%Y-%m-%d %h:%i:%s')) AS fecha_entrada,
            upper(noparte) AS numero_parte,
            remate,
            spif AS spiff,
            upper(subclave) AS subclave,
            Codigo AS upc,
            width AS ancho,
            height AS alto,
            length AS largo,
            weight AS peso,
        CASE
            WHEN upper(Tiempogar) LIKE '1%MES%' THEN 6
            WHEN upper(Tiempogar) LIKE '3%MES%' THEN 8
            WHEN upper(Tiempogar) LIKE '4%MES%' THEN 9
            WHEN upper(Tiempogar) LIKE '6%MES%' THEN 11
            WHEN upper(Tiempogar) LIKE '1%ÑO%' THEN 12
            WHEN upper(Tiempogar) LIKE '5%ÑO%' THEN 14
            WHEN upper(Tiempogar) LIKE '7%ÑO%' THEN 14
            WHEN upper(Tiempogar) LIKE '%SER%' THEN 2
            WHEN upper(Tiempogar) LIKE '1%IA%' THEN 3
            WHEN upper(Tiempogar) LIKE '1%SEM%' THEN 5
            ELSE 1
        END AS tipo_garantia_id,
        Marca AS marca_clave,
        categorias.categoria AS margen_nombre,
        Subfamilia AS subfamilia_clave,
        costo,
        precio1 AS precio_1,
        precio2 AS precio_2,
        precio3 AS precio_3,
        precio4 AS precio_4,
        precio5 AS precio_5,
        precio6 AS precio_6,
        precio7 AS precio_7,
        precio8 AS precio_8,
        precio9 AS precio_9,
        precio10 AS precio_10
    FROM
        productos
    LEFT JOIN
        categorias ON productos.categoria = categorias.clave;");
        $marcas = App\Marca::all()->toArray();
        $margenes = App\Margen::all()->toArray();
        $subfamilias = App\Subfamilia::all()->toArray();
        $this->reindexar('upc', $productos_legacy);
        $this->reindexar('clave', $marcas);
        $this->reindexar('nombre', $margenes);
        $this->reindexar('clave', $subfamilias);
        $this->foreignKeys($productos_legacy, $marcas, $margenes, $subfamilias);
        $this->crearProductos($productos_legacy);
    }

    /**
     * Reindexa un array usando el valor de uno de sus campos
     * @param String $key
     * @param array $result_set
     */
    private function reindexar($key, &$result_set) {
        $reindexed = array_map(function ($element) {
            return (array) $element;
        }, $result_set);
        $result_set = array_column($reindexed, null, $key);
    }

    /**
     * Obtiene los id's de las tablas relacionadas con foreign keys usando las claves extraídas del sistema legacy
     * @param array $productos
     * @param array $marcas
     * @param array $margenes
     * @param array $subfamilias
     */
    private function foreignKeys(&$productos, $marcas, $margenes, $subfamilias) {
        $unidad_id = App\Unidad::first()->id;
        foreach ($productos as &$producto) {
            $producto['marca_id'] = $marcas[strtoupper($producto['marca_clave'])]['id'];
            if ($producto['margen_nombre']) {
                $producto['margen_id'] = $margenes[$producto['margen_nombre']]['id'];
            }
            if (!empty($subfamilias[$producto['subfamilia_clave']])) {
                $producto['subfamilia_id'] = $subfamilias[$producto['subfamilia_clave']]['id'];
            }
            $producto['unidad_id'] = $unidad_id;

            unset($producto['marca_clave']);
            unset($producto['margen_nombre']);
            unset($producto['subfamilia_clave']);
        }
    }

    /**
     * Crea las instancias para los productos y les asocia sus dimensiones correspondientes
     * @param array $productos
     */
    private function crearProductos($productos) {
        $dimension_keys = [
            'largo' => null,
            'ancho' => null,
            'alto'  => null,
            'peso'  => null
        ];
        $precios_keys = [
            'costo'     => null,
            'precio_1'  => null,
            'precio_2'  => null,
            'precio_3'  => null,
            'precio_4'  => null,
            'precio_5'  => null,
            'precio_6'  => null,
            'precio_7'  => null,
            'precio_8'  => null,
            'precio_9'  => null,
            'precio_10' => null
        ];
        $exitosos = 0;
        $con_error = 0;
        foreach ($productos as $producto) {
            $dimension = array_intersect_key($producto, $dimension_keys);
            $dimension = [
                'largo' => $dimension['largo'] < 0.1 ? 0.1 : $dimension['largo'],
                'ancho' => $dimension['ancho'] < 0.1 ? 0.1 : $dimension['ancho'],
                'alto'  => $dimension['alto'] < 0.1 ? 0.1 : $dimension['alto'],
                'peso'  => $dimension['peso'] < 0.1 ? 0.1 : $dimension['peso']
            ];

            $precio = array_intersect_key($producto, $precios_keys);
            $producto = array_diff_key($producto, array_merge($dimension_keys, $precios_keys));
            if(!is_null($producto)){
            $producto_nuevo = new App\Producto($producto);

            }

            if ($producto_nuevo->guardarNuevo(['dimension' => $dimension, 'precio' => $precio])) {
                $exitosos ++;
            } else {
                $con_error ++;
            }
            $this->command->getOutput()->write("\rSeeding Productos, <info>Successful : {$exitosos}</info>. <error> Errors : {$con_error}</error>");
        }
    }
}

<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\MetodoPago::class, function ($faker) {
    return [
        'clave'                 => $faker->unique()->regexify('/[a-zA-Z0-9]{10}/'),
        'nombre'                => $faker->optional()->word,
        'comision'              => $faker->randomFloat(2, 0.000, 1.000),
        'monto_minimo'          => $faker->randomFloat(2, 1.00, 99999999.99),
        'informacion_adicional' => $faker->optional()->text(80),
        'estatus_activo_id'     => factory(App\EstatusActivo::class)->create()->id
    ];
});

$factory->defineAs(App\MetodoPago::class, 'nombrelargo', function ($faker) use ($factory) {
    $metodo_pago = $factory->raw(App\MetodoPago::class);

    return array_merge($metodo_pago, [
        'nombre' => $faker->text(100)
    ]);
});

$factory->defineAs(App\MetodoPago::class, 'clavelarga', function ($faker) use ($factory) {
    $metodo_pago = $factory->raw(App\MetodoPago::class);

    return array_merge($metodo_pago, [
        'clave' => $faker->unique()->text(100)
    ]);
});

$factory->defineAs(App\MetodoPago::class, 'descripcionlarga', function ($faker) use ($factory) {
    $metodo_pago = $factory->raw(App\MetodoPago::class);

    return array_merge($metodo_pago, [
        'informacion_adicional' => $faker->text(300)
    ]);
});

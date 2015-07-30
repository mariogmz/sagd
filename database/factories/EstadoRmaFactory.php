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

$factory->define(App\EstadoRma::class, function ($faker)
{
    return [
        'nombre' => $faker->unique()->text(45)
    ];
});

$factory->defineAs(App\EstadoRma::class, 'nombrelargo', function ($faker) use ($factory)
{
    return [
        'nombre' => $faker->unique()->text(100)
    ];
});


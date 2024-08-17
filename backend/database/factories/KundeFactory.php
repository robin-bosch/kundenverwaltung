<?php

namespace Database\Factories;

use App\Models\Kunde;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kunde>
 */
class KundeFactory extends Factory
{
    protected $model = Kunde::class;

    public function definition()
    {
        return [
            'vorname' => $this->faker->firstName,
            'nachname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'telefonnummer' => $this->faker->phoneNumber,
            'strasse' => $this->faker->streetAddress,
            'plz' => $this->faker->postcode,
            'ort' => $this->faker->city,
        ];
    }
}

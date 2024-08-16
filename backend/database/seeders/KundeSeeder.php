<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KundeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('de_DE');

        foreach (range(1, 50) as $index) {
            DB::table('kundes')->insert([
                'vorname' => $faker->firstName,
                'nachname' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'telefonnummer' => $faker->optional()->phoneNumber,
                'strasse' => $faker->streetAddress,
                'plz' => $faker->postcode,
                'ort' => $faker->city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

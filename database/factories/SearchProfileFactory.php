<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SearchProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fields = [
            "area" =>$this->faker->randomElement([
                    [
                        $this->faker->randomElement([$this->faker->numberBetween(20,100000), null]), 
                        $this->faker->randomElement([$this->faker->numberBetween(100000,200000), null])
                    ],
                    $this->faker->randomElement([$this->faker->numberBetween(20,200000), null]) 
            ]),
            "yearOfConstruction" => $this->faker->randomElement([
                [
                    $this->faker->randomElement([$this->faker->year("-5 years"), null]),
                    $this->faker->randomElement([$this->faker->year("+5 years"), null])
                ], 
                $this->faker->randomElement([$this->faker->year(), null])
            ]),
            "rooms" => $this->faker->randomElement([
                    [
                        $this->faker->randomElement([$this->faker->numberBetween(1, 6), null]),
                        $this->faker->randomElement([$this->faker->numberBetween(7, 12), null])
                    ],
                    $this->faker->randomElement([$this->faker->numberBetween(1, 12), null])
            ]),
            "heatingType" => $this->faker->randomElement([$this->faker->randomElement(["Furnace", "Boiler", "Heat Pump", " Hybrid Heating", "Radiant Heating", "Baseboard Heaters"]), null]),
            "parking" => $this->faker->randomElement([$this->faker->boolean(), null]),
            "returnActual" => $this->faker->randomElement([
                [
                    $this->faker->randomElement([$this->faker->randomFloat(2, 1, 25), null]),
                    $this->faker->randomElement([$this->faker->randomFloat(2, 26, 50), null])
                ],
                $this->faker->randomElement([$this->faker->randomFloat(2, 1, 50), null])
             ]),
            "price" => $this->faker->randomElement([
                [
                    $this->faker->randomElement([$this->faker->numberBetween(1,59999999), null]),
                    $this->faker->randomElement([$this->faker->numberBetween(1,60000000), null])
                ],
                $this->faker->randomElement([$this->faker->numberBetween(1,99999999), null])
            ]),
        ];

        $offset = $this->faker->numberBetween(0, 3);
        $length = $this->faker->numberBetween(0, count($fields) - 1 - $offset);

        return [
            'name' => $this->faker->company(),
            'property_type_id' => $this->faker->numberBetween(1, PropertyType::count()),
            'search_fields' =>  array_slice($fields,$offset, $length, true),
        ];
    }
}

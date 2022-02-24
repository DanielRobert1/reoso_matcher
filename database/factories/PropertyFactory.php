<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fields = [
            "area" => $this->faker->randomElement([$this->faker->randomNumber(6), null]),
            "yearOfConstruction" => $this->faker->randomElement([$this->faker->year(), null]),
            "rooms" => $this->faker->randomElement([$this->faker->numberBetween(1, 12), null]),
            "heatingType" => $this->faker->randomElement([$this->faker->randomElement(["Furnace", "Boiler", "Heat Pump", " Hybrid Heating", "Radiant Heating", "Baseboard Heaters"]), null]),
            "parking" => $this->faker->randomElement([$this->faker->boolean(), null]),
            "returnActual" => $this->faker->randomElement([$this->faker->randomFloat(2, 1, 50), null]),
            "price" => $this->faker->randomElement([$this->faker->randomNumber(8), null])
        ];

        $offset = $this->faker->numberBetween(0, 3);
        $length = $this->faker->numberBetween(0, count($fields) - 1 - $offset);

        return [
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'property_type_id' => $this->faker->numberBetween(1, PropertyType::count()),
            'fields' => json_encode(array_slice($fields,$offset, $length, true)),
        ];
    }
}

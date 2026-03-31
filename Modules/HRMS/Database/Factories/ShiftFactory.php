<?php

namespace Modules\HRMS\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\HRMS\Models\Settings\Shift;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\HRMS\Models\Settings\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shift::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define common shift patterns
        $shifts = [
            ['name' => 'Day Shift', 'in_time' => '09:00:00', 'out_time' => '18:00:00'],
            ['name' => 'Night Shift', 'in_time' => '20:00:00', 'out_time' => '05:00:00'],
            ['name' => 'Evening Shift', 'in_time' => '14:00:00', 'out_time' => '23:00:00'],
            ['name' => 'Morning Shift', 'in_time' => '06:00:00', 'out_time' => '15:00:00'],
            ['name' => 'General', 'in_time' => '09:00:00', 'out_time' => '18:00:00'],
        ];
        
        $shift = $this->faker->randomElement($shifts);

        return [
            'shift_name' => $shift['name'] . ' ' . $this->faker->word(),
            'grace_time' => $this->faker->numberBetween(10, 30),
            'in_time' => $shift['in_time'],
            'out_time' => $shift['out_time'],
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    /**
     * Indicate that the shift is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
        ]);
    }

    /**
     * Indicate that the shift is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }
}
<?php

// namespace Modules\HRMS\Database\Factories;

// use Illuminate\Database\Eloquent\Factories\Factory;
// use Modules\HRMS\Models\Attendance;
// use Modules\HRMS\Models\Employee;
// use Modules\HRMS\Models\Settings\Shift;

// /**
//  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\HRMS\Models\Attendance>
//  */
// class AttendanceFactory extends Factory
// {
//     /**
//      * The name of the factory's corresponding model.
//      *
//      * @var string
//      */
//     protected $model = Attendance::class;

//     /**
//      * Define the model's default state.
//      *
//      * @return array<string, mixed>
//      */
//     public function definition(): array
//     {
//         // Get a random employee
//         $employee = Employee::inRandomOrder()->first() ?? Employee::factory()->create();
        
//         // Get a random shift
//         $shift = Shift::inRandomOrder()->first() ?? Shift::factory()->create();
        
//         // Generate a random date within the last year
//         $date = $this->faker->dateTimeBetween('-1 year', 'now');
        
//         // Randomly determine attendance status
//         $statuses = ['present', 'absent', 'late', 'early_departure', 'half_day'];
//         $status = $this->faker->randomElement($statuses);
        
//         // Generate check-in and check-out times based on shift
//         $checkInTime = null;
//         $checkOutTime = null;
//         $checkInDate = null;
//         $checkOutDate = null;
        
//         if ($status !== 'absent') {
//             // For present or late employees, generate check-in/out times
//             $shiftInTime = $shift->in_time ? new \DateTime($shift->in_time) : new \DateTime('09:00:00');
//             $shiftOutTime = $shift->out_time ? new \DateTime($shift->out_time) : new \DateTime('18:00:00');
            
//             // Adjust for late arrivals or early departures
//             if ($status === 'late') {
//                 // Arrive 15-60 minutes late
//                 $lateMinutes = $this->faker->numberBetween(15, 60);
//                 $shiftInTime->modify('+' . $lateMinutes . ' minutes');
//             } elseif ($status === 'early_departure') {
//                 // Leave 15-60 minutes early
//                 $earlyMinutes = $this->faker->numberBetween(15, 60);
//                 $shiftOutTime->modify('-' . $earlyMinutes . ' minutes');
//             }
            
//             $checkInTime = $shiftInTime->format('H:i:s');
//             $checkOutTime = $shiftOutTime->format('H:i:s');
//             $checkInDate = $date->format('Y-m-d');
//             $checkOutDate = $date->format('Y-m-d');
            
//             // Handle overnight shifts
//             if ($shiftOutTime < $shiftInTime) {
//                 $checkOutDate = (clone $date)->modify('+1 day')->format('Y-m-d');
//             }
//         }

//         return [
//             'employee_id' => $employee->id,
//             'date' => $date->format('Y-m-d'),
//             'remarks' => $this->faker->optional()->sentence(),
//             'check_in_date' => $checkInDate,
//             'check_in_time' => $checkInTime,
//             'check_in_latitude' => $this->faker->optional()->latitude(),
//             'check_in_longitude' => $this->faker->optional()->longitude(),
//             'check_out_date' => $checkOutDate,
//             'check_out_time' => $checkOutTime,
//             'check_out_latitude' => $this->faker->optional()->latitude(),
//             'check_out_longitude' => $this->faker->optional()->longitude(),
//             'status' => $status,
//             'attendance_type' => $this->faker->optional()->randomElement(['regular', 'remote', 'field', 'leave']),
//             'shift_id' => $shift->id,
//             'created_by' => 1,
//             'updated_by' => 1,
//         ];
//     }

//     /**
//      * Indicate that the employee was present.
//      */
//     public function present(): static
//     {
//         return $this->state(fn (array $attributes) => [
//             'status' => 'present',
//         ]);
//     }

//     /**
//      * Indicate that the employee was absent.
//      */
//     public function absent(): static
//     {
//         return $this->state(fn (array $attributes) => [
//             'status' => 'absent',
//             'check_in_date' => null,
//             'check_in_time' => null,
//             'check_out_date' => null,
//             'check_out_time' => null,
//         ]);
//     }

//     /**
//      * Indicate that the employee was late.
//      */
//     public function late(): static
//     {
//         return $this->state(fn (array $attributes) => [
//             'status' => 'late',
//         ]);
//     }

//     /**
//      * Indicate that the employee left early.
//      */
//     public function earlyDeparture(): static
//     {
//         return $this->state(fn (array $attributes) => [
//             'status' => 'early_departure',
//         ]);
//     }

//     /**
//      * Indicate that the employee worked a half day.
//      */
//     public function halfDay(): static
//     {
//         return $this->state(fn (array $attributes) => [
//             'status' => 'half_day',
//         ]);
//     }
// }
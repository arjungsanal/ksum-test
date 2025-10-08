<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['paid', 'pending', 'failed'];
        $status = $this->faker->randomElement($statuses);

        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('##########'),
            // CORRECTED: Using lowercase 'male' and 'female' to match database enum/check constraints
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'),
            'short_bio' => $this->faker->sentence(10),
            'resume_path' => 'resumes/dummy_resume_' . $this->faker->uuid() . '.pdf',
            'razorpay_order_id' => 'order_' . $this->faker->regexify('[a-zA-Z0-9]{14}'),
            'razorpay_payment_id' => $status === 'paid'
                ? 'pay_' . $this->faker->regexify('[a-zA-Z0-9]{14}')
                : null,
            'status' => $status,
        ];
    }
}

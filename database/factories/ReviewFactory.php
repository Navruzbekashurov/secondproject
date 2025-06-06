<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = Carbon::instance(fake()->dateTimeBetween('-2 years'));

        return [
             'book_id'=>null,
            'review'=>fake()->paragraph,
            'rating'=>fake()->numberBetween(1,5),
            'created_at'=>$createdAt,
            'updated_at'=>fake()->dateTimeBetween($createdAt, 'now')
        ];
    }

    public function good()
    {
        return $this->state(function (array $attributes){
           return [
                'rating'=>fake()->numberBetween(4,5)
            ];
        });

    }
    public function average()
    {
        return $this->state(function (array $attributes){
           return [
                'rating'=>fake()->numberBetween(2,5)
            ];
        });

    }

    public function bad()
    {
        return $this->state(function (array $attributes){
            return [
                'rating'=>fake()->numberBetween(1,3)
            ];
        });

    }
}

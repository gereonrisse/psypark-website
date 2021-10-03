<?php

namespace Database\Factories;

use App\Models\PostingItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostingItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostingItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        return [
            'user_id'     => $this->faker->randomElement($users),
            'description' => $this->faker->sentence(4, true),
            'person'      => $this->faker->name(),
            'notes'       => $this->faker->paragraph(3, true),
            'amount'      => $this->faker->randomFloat(2, -1000, 1000),
            'datetime'    => $this->faker->dateTimeBetween('-10 years', 'now'),
            'file_hash'   => null
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $active = [0, 1];
        $status = ['published', 'draft'];

        return [
            'user_id' => $this->faker->randomElement($users),
            'title' => $this->faker->sentence(),
            'body' => $this->faker->text(),
            'active' => $this->faker->randomElement($active),
            'status' => $this->faker->randomElement($status),
        ];
    }
}

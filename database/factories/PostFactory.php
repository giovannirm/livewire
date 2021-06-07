<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(),
            'image' => 'posts/' . $this->faker->image('public/storage/posts', 640, 480, null, false),  //posts/Image1
            //Esto es lo que se almacenaría en el campo image si es true o false
            //true -> public/storage/posts/Image1
            //false -> Image1
        ];
    }
}

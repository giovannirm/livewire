<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* Eliminamos la carpeta posts en caso exista, ya que sino
        se generarían varias imágenes en la carpeta posts al
        ejecutar las migraciones */
        Storage::deleteDirectory('posts');
        /* Creará la carpeta posts en public/storage */
        Storage::makeDirectory('posts');
        
        // \App\Models\User::factory(10)->create();
        \App\Models\Post::factory(100)->create();
    }
}

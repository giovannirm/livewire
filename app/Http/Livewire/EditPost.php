<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class EditPost extends Component
{
    use WithFileUploads;
    public $open = false;

    public $post, $image, $identify;


    protected $rules = [
        'post.title' => 'required|max:100',
        'post.content' => 'required|max:200',
        'image' => 'nullable|image|mimes:jpg,bmp,jpeg,png|max:2048',
    ];

    protected $validationAttributes = [
        'post.title' => 'título',
        'post.content' => 'contenido',
        'image' => 'imagen',
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->identify = rand();
    }

    public function updated($propertyName)
    {
        try {
            $this->validateOnly($propertyName);
        } catch (ValidationException $e) {
            if ($propertyName == 'image') {
                $this->reset($propertyName);
            }
            throw $e;
        }
    }

    public function save()
    {
        $this->validate();

        //Estamos diciendo si hay algo en la propiedad image
        if ($this->image) {
            /* Elimino la imagen accediendo a ella*/
            Storage::delete([$this->post->image]);
            /* Subo la nueva imagen */
            $this->post->image = $this->image->store('posts');
        }

        $this->post->save();
        $this->reset(['open', 'image']);

        $this->identify = rand();

        $this->emitTo('show-posts', 'render');

        $this->emit('alert', 'El post se actualizó satisfactoriamente');
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}

<?php

namespace App\Http\Livewire;

use App\Http\Requests\CreatePost as RequestsCreatePost;
use App\Models\Post;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/* Importamos la clase para las imágenes */
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    /* Para usarlo dentro de este componente */
    use WithFileUploads;

    public $open = false;
    /* public $imageStatus = false; */

    public $title, $content, $image, $identify;

    public function mount()
    {
        $this->identify = rand();
    }
     
    /* protected $rules = [
        'title' => 'required|max:10',
        'content' => 'required|min:100',
    ]; */

    /* Este método se activa cada vez que se modifique alguna de 
    las propiedades definidas */
    /* public function updated($propertyName)
    {
        $r = new RequestsCreatePost(); */
        /* Valida que la propiedad pasada como parámetro cumpla con 
        las reglas de validación */
        /* $this->validateOnly($propertyName, $r->rules(), null, $r->attributes());
    } */

    
    public function updated($propertyName)
    {
        $r = new RequestsCreatePost();
        try {
            $this->validateOnly($propertyName, $r->rules(), null, $r->attributes());
        } catch (ValidationException $e) {
            if ($propertyName == 'image') {
                $this->reset($propertyName);
            }    
            throw $e;
        }
    }

    public function save()
    {
        $r = new RequestsCreatePost();
        $this->validate($r->rules(), null, $r->attributes());
        
        /* Recogemos la propiedad image, luego con el método store
        se guardará la imagen en la carpeta posts, y por último
        guardamos esa ruta en la variable image */        
        $image = $this->image->store('posts');

        Post::create([
            'title' => $this->title,
            'content' => $this->content,
            'image' => $image,
        ]);

        $this->reset(['open', 'title', 'content', 'image']);

        $this->identify = rand();
        
        /* Esto puede llamar a todos los métodos render de los componentes que estén comunicándose */
        /* $this->emit('render'); */

        $this->emitTo('show-posts', 'render');
        $this->emit('alert', 'El post se creó satisfactoriamente');
    }

    public function render()
    {
        return view('livewire.create-post');    
    }
}

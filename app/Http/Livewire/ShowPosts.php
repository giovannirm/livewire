<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

class ShowPosts extends Component
{
    /* public $title; */
    /* public $titulo;

    public function mount($title)
    {
        $this->titulo = $title;
    }
 */

    /* public $name;
    
    public function mount($name)
    {
        $this->name = $name;
    } */

    public $search;

    public $sort = 'id';

    public $direction = 'desc';

    /* El primer parámetro es el nombre del evento que se ha emitido
    El segundo parámetro es el método de este componente a ejecutarse
    protected $listeners = ['render' => 'render']; */

    /* Livewire va a entender lo mismo siempre y cuando el nombre
    del evento sea igual que el nombre del método a ejecutar*/
    protected $listeners = ['render'];

    public function render()
    {
        $posts = Post::where('title', 'like', '%' . $this->search . '%')
                        ->orwhere('content', 'like', '%' . $this->search . '%')
                        ->orderBy($this->sort, $this->direction)
                        ->get();

        return view('livewire.show-posts', compact('posts'));
    }

    public function order($sort)
    {
        if($this->sort == $sort)
        {
            $this->direction = $this->direction == 'desc' ? 'asc' : 'desc';
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    /* public function render()
    {
        return view('livewire.show-posts')
                ->layout('layouts.base');
    } */
}

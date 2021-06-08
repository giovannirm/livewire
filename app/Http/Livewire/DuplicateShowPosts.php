<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class DuplicateShowPosts extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $post, $image, $identify;

    public $search = '';

    public $sort = 'id';

    public $direction = 'desc';

    public $lot = '10';

    public $readyToLoad = false;

    public $open_edit = false;

    /* Indicamos que propiedades buscamos que viajen por la url */
    protected $queryString = [
        'lot' => ['except' => '10'],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->identify = rand();
        $this->post = new Post();
    }

    public function updatingSearch()
    {
        /* Con esto se hará que mientras escribamos algo en el buscador
        se elimine la información de la página */
        $this->resetPage();
    }

    public function updatingLot()
    {
        $this->resetPage();
    }

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

    protected $listeners = ['render', 'delete'];

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

    public function render()
    {
        if ($this->readyToLoad) {
            $posts = Post::where('title', 'like', '%' . $this->search . '%')
                ->orwhere('content', 'like', '%' . $this->search . '%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->lot);
        } else {
            $posts = [];
        }


        return view('livewire.duplicate-show-posts', compact('posts'));
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            $this->direction = $this->direction == 'desc' ? 'asc' : 'desc';
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function edit(Post $post)
    {
        $this->post = $post;
        $this->open_edit = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('posts');
        }

        $this->post->save();

        $this->reset(['open_edit', 'image']);

        $this->identify = rand();

        $this->emit('alert', 'El post se actualizó satisfactoriamente');
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function delete(Post $post)
    {
        $post->delete();
    }
}

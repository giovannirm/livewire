<div>
    <a class="btn btn-green" wire:click="$set('open', true)">
        <i class="fas fa-edit"></i>
    </a>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar el post
        </x-slot>

        <x-slot name="content">
            <div wire:loading wire:target="image"
                class="mb-4 w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se haya procesado</span>
            </div>
            
            @if ($image)
                <img class="ml-auto mr-auto mb-4 rounded-lg" src="{{ $image->temporaryUrl() }}">
            @else
                <img class="ml-auto mr-auto mb-4 rounded-lg" src="{{ Storage::url($post->image) }}" alt="">
            @endif

            <div class="mb-4">
                <x-jet-label value="Título del post" />
                <x-jet-input wire:model="post.title" type="text" class="w-full" />
                <x-jet-input-error for="post.title" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Contenido del post" />
                <textarea wire:model="post.content" class="form-control w-full" rows="6"></textarea>
                <x-jet-input-error for="post.content" />
            </div>

            <div>
                <input type="file" wire:model="image" id="{{ $identify }}">
                <x-jet-input-error for="image" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" class="disabled:opacity-25">
                Actualizar
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>

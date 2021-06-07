<div>
    <x-jet-danger-button wire:click="$set('open', 'true')">
        Crear nuevo post
    </x-jet-danger-button>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo post
        </x-slot>

        <x-slot name="content">
            
            <div wire:loading wire:target="image"
                class="mb-4 w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se haya procesado</span>
            </div>

            @if ($image)
                {{-- @php
                    try {
                        $url = $image->temporaryUrl();
                        $imageStatus = true;
                    } catch (RuntimeException $exception) {
                        $imageStatus = false;
                    }
                @endphp
                @if ($imageStatus)
                    <img class="mb-4 rounded-lg" src="{{ $url }}">
                @else
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Ocurrió un error!</strong>
                        <span class="block sm:inline">Ha subido contenido inválido</span>
                    </div>
                @endif --}}
                <img class="ml-auto mr-auto mb-4 rounded-lg" src="{{ $image->temporaryUrl() }}">
            @endif

            <div class="mb-4">
                <x-jet-label value="Título del post" />
                {{-- Acá se puso defer para evitar que el archivo se siga renderizando --}}
                {{-- <x-jet-input class="w-full" type="text" wire:model.defer="title" /> --}}
                <x-jet-input class="w-full" type="text" wire:model="title" />

                <x-jet-input-error for="title" />
            </div>
            {{$content}}
            <div class="mb-4" wire:ignore>
                <x-jet-label value="Contenido del post" />
                <textarea id="editor" 
                {{-- Cada vez que cambiemos algo de nuestra componente y se 
                tenga que renderizar la página, se renderice todo menos 
                la propiedad que tenga el wire:ignore --}}                        
                        rows="6" 
                        class="w-full form-control" 
                        wire:model="content">
                </textarea>

                <x-jet-input-error for="content" />
            </div>

            {{-- <div class="flex w-full items-center justify-center bg-white">
                <label class="w-64 flex flex-col items-center px-4 py-4 bg-white text-blue-500 rounded-lg shadow-lg tracking-wide uppercase border border-blue-400 cursor-pointer hover:bg-blue-400 hover:text-white">
                    <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <span class="mt-2 text-base leading-normal">Select a file</span>
                    <input class="hidden" type="file" wire:model="image" id="{{ $identify }}">
                </label>
                <x-jet-input-error for="image" />
            </div> --}}
            
            <div>
                <input type="file" wire:model="image" id="{{ $identify }}">

                <x-jet-input-error for="image" />
            </div>

        </x-slot>

        <x-slot name="footer">
            {{-- Es un método mágico para no estar creando métodos 
            en el controlador, el primer parámetro es la variable a 
            cambiar, el segundo parámetro es el valor nuevo de esa variable --}}
            <x-jet-secondary-button wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            {{-- esto es para el .flex <x-jet-danger-button wire:click="save"> --}}

            {{-- Se está indicando que mientras dure el proceso, se oculte este
            botón solo cuando se ejecute el método save --}}
            {{-- <x-jet-danger-button wire:click="save" wire:loading.remove wire:target="save"> --}}

            {{-- Se está indicando que mientras dure el proceso, se cambie
                la clase de este botón solo cuando se ejecute el método save --}}
            {{-- <x-jet-danger-button wire:click="save" wire:loading.class="bg-blue-500 hover:bg-blue-500" wire:target="save"> --}}

            {{-- Le agrega un atributo ya no una clase, acá el usuario podrá
                hacer muchos clicks pero solo el primero será efectivo --}}
            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" wire:target="save, image"
                class="disabled:opacity-25">
                Crear post
            </x-jet-danger-button>

            {{-- wire:loading -> por defecto livewire le agrega un 
                display:hidden para el span que se tiene por lo tanto estará
                oculto, vuelve a entrar en función cuando se realice alguna
                acción en el formulario sobre alguna propiedad que no tenga la propiedad
                defer, dando la propiedad display: inline block --}}

            {{-- wire:target -> sirve para especificar que se debe ejecutar
                cuando haya alguna acción sobre el método en específico --}}
            {{-- <span wire:loading.flex wire:target="save">Cargando...</span> --}}
            {{-- .flex -> cambia el display inline block por flex 
                grid table --}}

            {{-- <span wire:loading wire:target="save">Cargando...</span> --}}

        </x-slot>

    </x-jet-dialog-modal>

    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>

        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                /* Ponemos esta función ya que el wire:ignore ignora por
                completo el div donde está el textarea haciendo que no se
                almacene nada */
                .then(function(editor) {
                    /* Cada vez que haya un cambio en el editor, se desencadene
                    una acción, que lo escuche cada vez que se cambie
                    el campo data */
                    editor.model.document.on('change:data', () => {
                        /* Acá colocamos la acción que quiero que ocurra 
                        en este caso queremos que cada vez que cambiemos
                        algo en el editor, se cambie el valor de la propiedad
                        content, la cual es asignada al textarea*/
                        /* Llamamos al método mágico, con esto lograríamos
                        que cada vez que modifiquemos algo en el editor
                        tmb se vez modificado en la propiedad content */
                        @this.set('content', editor.getData());
                    })
                })
                .catch( error => {
                    console.error( error );
                } );
        </script>
    @endpush

</div>

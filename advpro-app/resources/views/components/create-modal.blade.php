{{-- resources/views/components/create-modal.blade.php --}}
@props(['modal_title' => 'Nuevo Registro', 'form_action' => '#'])

{{-- Capturamos el nombre de la variable x-show del padre --}}
@php
    $xShowVar = Str::after($attributes->get('x-show'), '='); // Extrae 'isModalOpen' o 'isAccountModalOpen'
    $xShowVar = trim($xShowVar, "'"); // Limpia comillas si las hay
@endphp

<div
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    {{ $attributes->whereStartsWith('x-show') }} {{-- Solo pasa el x-show del padre --}}
    {{-- APLICAMOS x-on:click.away y x-on:keydown.escape.window AQUÍ para cerrar el modal desde el overlay y con ESC --}}
    x-on:click.away="{{ $xShowVar }} = false"
    x-on:keydown.escape.window="{{ $xShowVar }} = false"
    class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
>
    <div
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform translate-y-1/2"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform translate-y-1/2"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
        role="dialog"
        id="modal"
        style="width: 80%; padding-bottom: 0;"
    >
        <header class="flex justify-end">
            <button
                class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
                aria-label="close"
                @click="{{ $xShowVar }} = false" {{-- USA LA VARIABLE CORRECTA DEL PADRE --}}
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
                    <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </button>
        </header>
        <div class="mt-4 mb-6 ">
            <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                {{ $modal_title }}
            </p>
            <form action="{{ $form_action }}" method="POST">
                @csrf
                {{ $slot }}

                <footer style="margin-bottom: -1.8rem;"
                    class="flex flex-col items-center justify-end px-6 py-4 pb-0.5 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
                >
                    <button type="button"
                        @click="{{ $xShowVar }} = false" {{-- USA LA VARIABLE CORRECTA DEL PADRE --}}
                        class="w-full px-5 py-4 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
                    >
                        Cancelar
                    </button>
                    <x-button type="submit">
                        Guardar
                    </x-button>
                </footer>
            </form>
        </div>
    </div>
</div>
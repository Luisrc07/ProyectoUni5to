<x-layouts.app>

    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Personal Administrativo
    </h2>

    {{-- Contenedor principal con Alpine.js para gestionar solo el modal de creación --}}
    <div x-data="{ isCreateModalOpen: false }">

        {{-- Contenedor de botones de acción y el formulario de filtros en línea --}}
        <div class="flex flex-wrap items-end justify-between gap-4 mb-6"> {{-- items-end para alinear por la base --}}
            {{-- Botón para abrir el modal de creación --}}
            <x-button @click="isCreateModalOpen = true" type="button" class="order-1 h-10 px-5 py-2 flex items-center justify-center gap-2">
                Crear Personal
            </x-button>

            {{-- Formulario de Filtros en línea --}}
            <form action="{{ route('personal.index') }}" method="GET" class="flex items-end space-x-4 order-2">
                {{-- Campo para Cargo --}}
                <div class="flex flex-col">
                    <label for="cargo" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Cargo</label>
                    <select
                        name="cargo"
                        id="cargo"
                        class="block w-40 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                        <option value="">- Todos los Cargos -</option>
                        <option value="Producción" {{ request('cargo') == 'Producción' ? 'selected' : '' }}>Producción</option>
                        <option value="Dirección" {{ request('cargo') == 'Dirección' ? 'selected' : '' }}>Dirección</option>
                        <option value="Logística & Equipo" {{ request('cargo') == 'Logística & Equipo' ? 'selected' : '' }}>Logística & Equipo</option>
                        <option value="Guion y Desarrollo" {{ request('cargo') == 'Guion y Desarrollo' ? 'selected' : '' }}>Guion y Desarrollo</option>
                        <option value="Fotografía y Cámara" {{ request('cargo') == 'Fotografía y Cámara' ? 'selected' : '' }}>Fotografía y Cámara</option>
                        <option value="Sonido" {{ request('cargo') == 'Sonido' ? 'selected' : '' }}>Sonido</option>
                        <option value="Arte & Escenografía" {{ request('cargo') == 'Arte & Escenografía' ? 'selected' : '' }}>Arte & Escenografía</option>
                        <option value="Iluminación y Eléctricos" {{ request('cargo') == 'Iluminación y Eléctricos' ? 'selected' : '' }}>Iluminación y Eléctricos</option>
                        <option value="Postproducción" {{ request('cargo') == 'Postproducción' ? 'selected' : '' }}>Postproducción</option>
                    </select>
                </div>

                {{-- Campo para Estado --}}
                <div class="flex flex-col">
                    <label for="estado" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Estado</label>
                    <select
                        name="estado"
                        id="estado"
                        class="block w-40 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                        <option value="">- Todos los Estados -</option>
                        <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                {{-- Campo para Nombre o Documento --}}
                <div class="flex flex-col">
                    <label for="nombre_documento" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Nombre/Documento</label>
                    <input
                        type="text"
                        name="nombre_documento"
                        id="nombre_documento"
                        value="{{ request('nombre_documento') }}"
                        placeholder="Nombre o documento"
                        class="block w-48 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                </div>

                {{-- Botón para Aplicar Filtros --}}
                <button type="submit"
                    class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Aplicar Filtros
                </button>
            </form>

            {{-- Botón para generar reporte en PDF (rojo) --}}
            <a href="{{ route('personal.exportar-pdf', request()->query()) }}" target="_blank"
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 1 0 01-2-2z" />
                </svg>
                Generar Reporte
            </a>

            {{-- Botón para limpiar filtros (opcional, muestra si hay filtros activos) --}}
            @if (count(array_filter(request()->query())) > 0 && !empty(array_diff_key(request()->query(), ['page' => ''])))
                <a href="{{ route('personal.index') }}" class="h-10 px-5 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray flex items-center justify-center gap-2">
                    Limpiar Filtros
                </a>
            @endif
        </div>

        {{-- Muestra los filtros activos --}}
        @if (count(array_filter(request()->query())) > 0 && !empty(array_diff_key(request()->query(), ['page' => ''])))
            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-6 shadow-inner text-sm text-gray-600 dark:text-gray-300">
                <p class="font-semibold mb-2">Filtros Activos:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (request()->query() as $key => $value)
                        @if ($value && $key !== 'page')
                            <span class="bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 px-3 py-1 rounded-full flex items-center gap-1">
                                {{ ucfirst(str_replace(['_id', '_'], ['', ' '], $key)) }}:
                                <span class="font-bold">{{ $value }}</span>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tabla del personal --}}
        <div class="w-full rounded-lg shadow-xs overflow-x-auto lg:overflow-visible">
            <div class="w-full rounded-lg shadow-xs overflow-x-auto xl:overflow-visible">
                <table class="w-full whitespace-nowrap min-w-[800px] xl:min-w-full xl:whitespace-normal">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3 w-1/6">Nombre</th>
                            <th class="px-4 py-3 w-1/6">Documento</th>
                            <th class="px-4 py-3 w-1/6">Email</th>
                            <th class="px-4 py-3 w-1/6">Teléfono</th>
                            <th class="px-4 py-3 w-1/6">Dirección</th>
                            <th class="px-4 py-3 w-1/6">Cargo</th>
                            <th class="px-4 py-3 w-1/6">Estado</th>
                            <th class="px-4 py-3 w-1/12">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse ($staff as $personal)
                            @include('components.personal.table-row', ['personal' => $personal])
                        @empty
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td colspan="8" class="px-4 py-3 text-center">No hay personal para mostrar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación de Laravel --}}
        <div class="mt-4">
            {{ $staff->appends(request()->except('page'))->links() }}
        </div>

        {{-- MODAL DE CREACIÓN (Usa la estructura directa, sin x-create-modal) --}}
        <div
            x-show="isCreateModalOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
            @click.away="isCreateModalOpen = false"
            @keydown.escape.window="isCreateModalOpen = false"
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
                id="modal-create"
                style="width: 80%; padding-bottom: 0;"
            >
                <header class="flex justify-end">
                    <button
                        class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover:text-gray-700"
                        aria-label="close"
                        @click="isCreateModalOpen = false"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
                            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                    </button>
                </header>
                <div class="mt-4 mb-6">
                    <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Crear Personal
                    </p>
                    <form action="{{ route('personal.store') }}" method="POST">
                        @csrf
                        @include('components.personal.create-form-fields')

                        <footer style="margin-bottom: -1.8rem;"
                            class="flex flex-col items-center justify-end px-6 py-4 pb-0.5 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
                        >
                            <button type="button"
                                @click="isCreateModalOpen = false"
                                class="w-full px-5 py-4 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
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

        {{-- El MODAL DE FILTROS anterior ha sido eliminado y su contenido integrado arriba --}}

    </div> {{-- Cierre del div con x-data --}}

</x-layouts.app>
<x-layouts.app>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Proyectos
    </h2>

    {{-- Mensajes de alerta --}}
    @if(session('alert'))
        <div class="bg-{{ session('alert.type') == 'success' ? 'green' : 'red' }}-100 border border-{{ session('alert.type') == 'success' ? 'green' : 'red' }}-400 text-{{ session('alert.type') == 'success' ? 'green' : 'red' }}-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">{{ session('alert.title') }}</strong>
            <span class="block sm:inline">{{ session('alert.message') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-{{ session('alert.type') == 'success' ? 'green' : 'red' }}-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 010 1.698z"/></svg>
            </span>
        </div>
    @endif

    {{-- Contenedor principal con Alpine.js para gestionar el estado del modal de filtros --}}
    <div x-data="{ isFilterModalOpen: false, init() { console.log('Alpine initialized. isFilterModalOpen:', this.isFilterModalOpen); } }">
        {{-- Contenedor de botones de acción --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            {{-- Botón para ir a la página de creación de proyectos --}}
            <a href="{{ route('proyectos.create') }}" class="order-1 h-10 px-5 py-2 flex items-center justify-center gap-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Crear Proyecto
            </a>

            {{-- Contenedor de filtros y exportación --}}
            <div class="flex items-center space-x-4 order-2">
                {{-- Botón para abrir el modal de filtros --}}
                <x-button @click="isFilterModalOpen = true" type="button" class="h-10 px-5 py-2 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Filtrar
                </x-button>

                {{-- Formulario para exportar a PDF (oculto) --}}
                <form action="{{ route('proyectos.exportar-pdf') }}" method="GET" id="export-pdf-form">
                    {{-- Los campos se añadirán aquí con JS --}}
                </form>

                {{-- Botón para exportar a PDF --}}
                <x-button type="button" onclick="submitExportForm()"
                    class="h-10 px-5 py-2 bg-red-600 hover:bg-red-700 active:bg-red-600 focus:shadow-outline-red flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zM10 12V4a1 1 0 012 0v8h3a1 1 0 01.707 1.707l-4 4a1 1 0 01-1.414 0l-4-4A1 1 0 017 12h3z" clip-rule="evenodd" />
                    </svg>
                    Exportar PDF
                </x-button>

                {{-- Botón para limpiar filtros --}}
                @if (count(array_filter(request()->query())) > 0 && !empty(array_diff_key(request()->query(), ['page' => ''])))
                    <a href="{{ route('proyectos.index') }}" class="h-10 px-5 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray flex items-center justify-center gap-2">
                        Limpiar Filtros
                    </a>
                @endif
            </div>
        </div>

        {{-- Script para enviar el formulario de exportación con los filtros actuales --}}
        <script>
            function submitExportForm() {
                const filterForm = document.getElementById('filter-form'); // El formulario dentro del modal de filtros
                const exportForm = document.getElementById('export-pdf-form'); // El formulario oculto de exportación

                // Limpiar el formulario de exportación antes de copiar los campos para evitar duplicados
                exportForm.innerHTML = '';

                if (filterForm) {
                    const filterInputs = filterForm.querySelectorAll('input, select, textarea');
                    filterInputs.forEach(input => {
                        if (input.name && input.value) {
                            const clonedInput = input.cloneNode(true);
                            exportForm.appendChild(clonedInput);
                        }
                    });
                }
                exportForm.submit();
            }
        </script>

        {{-- Muestra los filtros activos --}}
        @if (count(array_filter(request()->query())) > 0 && !empty(array_diff_key(request()->query(), ['page' => ''])))
            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-6 shadow-inner text-sm text-gray-600 dark:text-gray-300">
                <p class="font-semibold mb-2">Filtros Activos:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (request()->query() as $key => $value)
                        @if ($value && $key !== 'page')
                            <span class="bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 px-3 py-1 rounded-full flex items-center gap-1">
                                {{ ucfirst(str_replace(['_id', '_', '_min', '_max'], ['',' ', ' Mín.', ' Máx.'], $key)) }}:
                                <span class="font-bold">
                                    {{-- Eliminado el bloque de fecha ya que los proyectos no tienen fechas --}}
                                    @if (Str::contains($key, 'presupuesto'))
                                        ${{ number_format($value, 2, ',', '.') }}
                                    @elseif ($key == 'responsable_id')
                                        {{ $personal->find($value)->nombre ?? 'N/A' }}
                                    @elseif ($key == 'personal_asignado_id')
                                        {{ $personal->find($value)->nombre ?? 'N/A' }}
                                    @elseif ($key == 'equipo_asignado_id')
                                        {{ $equipos->find($value)->nombre ?? 'N/A' }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Se actualizan los headers, con 'Duracion Est. Min.' movido y el colspan ajustado --}}
        <x-table :headers="['Nombre', 'Descripción', 'Estado', 'Duracion Est. Min.', 'Presupuesto', 'Lugar', 'Responsable', 'Personal Asignado', 'Equipos Asignados', 'Opciones']">
            @forelse ($proyectos as $project)
                @include('components.proyectos.table-row', ['item' => $project, 'route_prefix' => 'proyectos'])
            @empty
                <tr class="text-gray-700 dark:text-gray-400">
                    {{-- El colspan debe ser 10, ya que ahora hay 10 columnas en total (9 de tu lista original + la nueva 'Duracion Est. Min.') --}}
                    <td class="px-4 py-3 text-center" colspan="10">No hay proyectos para mostrar.</td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $proyectos->appends(request()->except('page'))->links() }}
        </div>

        {{-- MODAL DE FILTROS (Revertido a estructura inline para evitar problemas con el componente x-modal) --}}
        <div
            x-show="isFilterModalOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
            @click.away="isFilterModalOpen = false"
            @keydown.escape.window="isFilterModalOpen = false"
        >
            <div
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 transform translate-y-1/2"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 transform translate-y-1/2"
                class="w-full bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl max-h-[90vh] flex flex-col"
                role="dialog"
                id="modal-filter"
            >
                <header class="flex justify-end p-4">
                    <button
                        class="inline-flex items-center justify-2 w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover:text-gray-700"
                        aria-label="close"
                        @click="isFilterModalOpen = false"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
                            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                    </button>
                </header>
                <div class="px-6 py-4 flex-grow overflow-y-auto h-0 min-h-0">
                    <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                        Filtrar Proyectos
                    </p>
                    <form action="{{ route('proyectos.index') }}" method="GET" id="filter-form">
                        {{-- Asegúrate de que 'personal' y 'equipos' se estén pasando correctamente a filter-form-fields --}}
                        @include('components.proyectos.filter-form-fields', compact('personal', 'equipos'))
                    </form>
                </div>
                <footer class="flex flex-col items-center justify-end px-6 py-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800 rounded-b-lg">
                    <button type="button"
                        @click="isFilterModalOpen = false"
                        class="w-full px-5 py-4 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </button>
                    <x-button type="submit" form="filter-form" class="h-10 px-5 py-2 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Aplicar Filtros
                    </x-button>
                </footer>
            </div>
        </div>
    </div>
</x-layouts.app>
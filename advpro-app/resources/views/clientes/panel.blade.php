<x-layouts.app>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Clientes
    </h2>

    <div x-data="{ isModalOpen: false, openModal() { this.isModalOpen = true }, closeModal() { this.isModalOpen = false } }">

        {{-- Contenedor de Botones y Filtros (ESTRUCTURA ORIGINAL) --}}
        <div class="flex items-end justify-between mb-6">
            
            {{-- 1. Botón "Crear Cliente" (izquierda, usando el componente x-button) --}}
            <x-button @click="openModal" type="button">
                Crear Cliente
            </x-button>

            {{-- 2. Formulario de Filtros (centro) --}}
            <form action="{{ route('clientes.index') }}" method="GET" class="flex items-end space-x-4">
                
                {{-- Filtro por Tipo de Documento --}}
                <div class="flex flex-col">
                    <label for="tipo_documento_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Tipo de Doc.</label>
                    <select name="tipo_documento_filtro" id="tipo_documento_filtro"
                        class="block w-32 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                        <option value="">Todos</option>
                        <option value="V" {{ request('tipo_documento_filtro') == 'V' ? 'selected' : '' }}>V</option>
                        <option value="J" {{ request('tipo_documento_filtro') == 'J' ? 'selected' : '' }}>J</option>
                        <option value="E" {{ request('tipo_documento_filtro') == 'E' ? 'selected' : '' }}>E</option>
                        <option value="G" {{ request('tipo_documento_filtro') == 'G' ? 'selected' : '' }}>G</option>
                    </select>
                </div>

                {{-- Filtro de Búsqueda (por nombre o documento) --}}
                <div class="flex flex-col">
                    <label for="search_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Buscar (Nombre/Doc.)</label>
                    <input type="text" name="search" id="search_filtro"
                        value="{{ request('search') }}"
                        class="block w-48 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                        placeholder="Nombre o documento"
                    >
                </div>

                {{-- 3. Botón para Aplicar Filtros (con las clases del componente) --}}
                <button type="submit"
                    class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtrar
                </button>
            </form>

            {{-- 4. Botón "Generar Reporte" (derecha) --}}
            <a href="{{ route('clientes.reporte', request()->query()) }}" target="_blank"
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                Generar Reporte
            </a>
        </div>
        
        {{-- Generic table component for displaying the list of clients --}}
        <x-table :headers="['Nombre', 'Documento', 'Email', 'Telefono', 'Direccion', 'Opciones']">
            @forelse ($clientes as $client)
                @include('components.clientes.table-row', ['item' => $client, 'route_prefix' => 'clientes'])
            @empty
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-center" colspan="6">No hay clientes para mostrar.</td>
                </tr>
            @endforelse
        </x-table>

        {{-- Laravel pagination for clients --}}
        <div class="mt-4">
            {{ $clientes->links() }}
        </div>

        {{-- Generic creation modal component --}}
        <x-create-modal
            modal_title="Crear Cliente"
            form_action="{{ route('clientes.store') }}"
            x-show="isModalOpen"
            @click.away="closeModal"
            @keydown.escape.window="closeModal"
        >
            @include('components.clientes.create-form-fields')
        </x-create-modal>
    </div>
</x-layouts.app>
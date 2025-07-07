<x-layouts.app>

    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Equipos
    </h2>

    <div x-data="{ isModalOpen: false, openModal() { this.isModalOpen = true }, closeModal() { this.isModalOpen = false } }">

        {{-- Contenedor de Botones y Filtros --}}
        <div class="flex items-end justify-between mb-6">

            {{-- Botón "Nuevo Equipo" --}}
            <x-button @click="openModal" type="button" class="px-4 py-2">
                Nuevo Equipo
            </x-button>

            {{-- Formulario de Filtros --}}
            <form action="{{ route('equipos.index') }}" method="GET" class="flex items-end space-x-4">

                {{-- Filtro por Estado --}}
                <div class="flex flex-col items-start">
                    <label for="estado_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Estado</label>
                    <select name="estado" id="estado_filtro"
                        class="block w-40 h-9 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                        <option value="">Todos</option>
                        <option value="Nuevo" {{ request('estado') == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                        <option value="Usado" {{ request('estado') == 'Usado' ? 'selected' : '' }}>Usado</option>
                        <option value="Reparado" {{ request('estado') == 'Reparado' ? 'selected' : '' }}>Reparado</option>
                    </select>
                </div>

                {{-- Filtro por Fecha de Creación --}}
                <div class="flex flex-col items-start">
                    <label for="fecha_creacion_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Fecha de Creación</label>
                    <select name="fecha_creacion" id="fecha_creacion_filtro"
                        class="block w-40 h-9 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                        <option value="">Sin ordenar</option>
                        <option value="nuevos" {{ request('fecha_creacion') == 'nuevos' ? 'selected' : '' }}>Más Nuevos</option>
                        <option value="viejos" {{ request('fecha_creacion') == 'viejos' ? 'selected' : '' }}>Más Viejos</option>
                    </select>
                </div>

                {{-- Filtro por Tipo de Equipo --}}
                <div class="flex flex-col items-start">
                    <label for="tipo_equipo_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Tipo de Equipo</label>
                    <select name="tipo_equipo" id="tipo_equipo_filtro"
                        class="block w-40 h-9 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                        <option value="">Todos</option>
                        <option value="Cámara" {{ request('tipo_equipo') == 'Cámara' ? 'selected' : '' }}>Cámara</option>
                        <option value="Lente" {{ request('tipo_equipo') == 'Lente' ? 'selected' : '' }}>Lente</option>
                        <option value="Iluminación" {{ request('tipo_equipo') == 'Iluminación' ? 'selected' : '' }}>Iluminación</option>
                        <option value="Sonido" {{ request('tipo_equipo') == 'Sonido' ? 'selected' : '' }}>Sonido</option>
                        <option value="Trípode" {{ request('tipo_equipo') == 'Trípode' ? 'selected' : '' }}>Trípode</option>
                        <option value="Estabilizador" {{ request('tipo_equipo') == 'Estabilizador' ? 'selected' : '' }}>Estabilizador</option>
                        <option value="Micrófono" {{ request('tipo_equipo') == 'Micrófono' ? 'selected' : '' }}>Micrófono</option>
                        <option value="Monitor" {{ request('tipo_equipo') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                        <option value="Batería" {{ request('tipo_equipo') == 'Batería' ? 'selected' : '' }}>Batería</option>
                        <option value="Cable" {{ request('tipo_equipo') == 'Cable' ? 'selected' : '' }}>Cable</option>
                        <option value="Otro" {{ request('tipo_equipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                {{-- FILTRO POR RESPONSABLE --}}
                <div class="flex flex-col items-start">
                    <label for="responsable_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Responsable</label>
                    <select name="responsable" id="responsable_filtro"
                        class="block w-40 h-9 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                    >
                        <option value="">Todos</option>
                        @foreach ($personal as $persona)
                            <option value="{{ $persona->id }}"
                                {{ request('responsable') == $persona->id ? 'selected' : '' }}
                                class="truncate"
                            >
                                {{ $persona->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- FILTROS POR VALOR MIN Y MAX --}}
                <div class="flex flex-col items-start">
                    <label for="valor_min_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Valor Mín.</label>
                    <input type="number" name="valor_min" id="valor_min_filtro"
                        class="block w-28 h-9 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                        placeholder="Mín."
                        value="{{ request('valor_min') }}"
                        min="0" step="0.01"
                    />
                </div>

                <div class="flex flex-col items-start">
                    <label for="valor_max_filtro" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Valor Máx.</label>
                    <input type="number" name="valor_max" id="valor_max_filtro"
                        class="block w-28 h-9 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
                        placeholder="Máx."
                        value="{{ request('valor_max') }}"
                        min="0" step="0.01"
                    />
                </div>

                {{-- Botón para Aplicar Filtros --}}
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtrar
                </button>
            </form>

            {{-- Botón "Generar Reporte" --}}
            <a href="{{ route('equipos.generarReporte', request()->query()) }}" target="_blank"
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                PDF
            </a>
        </div>

        <x-table :headers="['Nombre', 'Descripción', 'Marca', 'Tipo de Equipo', 'Estado', 'Ubicación', 'Responsable','Valor', 'Acciones']">
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                @forelse ($equipos as $equipo)
                    @include('components.equipos.table-row', ['item' => $equipo, 'route_prefix' => 'equipos'])
                @empty
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td colspan="9" class="px-4 py-3 text-center">No hay equipos para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>

        <div class="mt-4">
            {{ $equipos->links() }}
        </div>

        <x-create-modal
            modal_title="Crear Equipo"
            form_action="{{ route('equipos.store') }}"
            x-show="isModalOpen"
            @click.away="closeModal"
            @keydown.escape.window="closeModal"
        >
            @include('components.equipos.create-form-fields', ['personal' => $personal])
        </x-create-modal>

    </div>
</x-layouts.app>
<x-layouts.app>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Módulo Contable
    </h2>

    {{-- Contenedor principal para los botones de acción y filtros --}}
    <div class="flex items-end justify-between mb-6">

        {{-- Contenedor para los botones de acción (Registrar Asiento, Nueva Cuenta) y sus modales --}}
        <div x-data="{ isModalOpen: false, isAccountModalOpen: false }" class="flex gap-4">
            {{-- Botón para Abrir Modal de Asiento --}}
            <x-button @click="isModalOpen = true" type="button">
                Registrar Asiento Manual
            </x-button>

            {{-- Botón para Abrir Modal de Cuenta Contable --}}
            <x-button @click="isAccountModalOpen = true" type="button" class="bg-green-600 hover:bg-green-700 active:bg-green-600 focus:shadow-outline-green">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nueva Cuenta Contable
            </x-button>

            {{-- Modal para Registrar Asiento Contable --}}
            <x-create-modal
                modal_title="Registrar Asiento Contable"
                form_action="{{ route('contabilidad.store') }}"
                x-show="isModalOpen"
                @click.away="isModalOpen = false"
                @keydown.escape.window="isModalOpen = false"
            >
                @include('components.contabilidad.create', ['cuentas' => $movimientoCuentas])
            </x-create-modal>

            {{-- Modal para Crear Cuenta Contable --}}
            <x-create-modal
                modal_title="Crear Nueva Cuenta Contable"
                form_action="{{ route('contabilidad.cuentas.store') }}"
                x-show="isAccountModalOpen"
                @click.away="isAccountModalOpen = false"
                @keydown.escape.window="isAccountModalOpen = false"
            >
                @include('components.contabilidad.create-cuenta', ['cuentas' => $todasCuentas])
            </x-create-modal>
        </div>

        {{-- Formulario de Filtros de Fecha --}}
        <form action="{{ route('contabilidad.index') }}" method="GET" class="flex items-end gap-3">
            <div class="flex flex-col">
                <label for="fecha_inicio" class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Desde:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                       class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
            </div>
            <div class="flex flex-col">
                <label for="fecha_fin" class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Hasta:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="{{ request('fecha_fin') }}"
                       class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
            </div>
            <button type="submit"
                    class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01.293.707V19a2 2 0 01-2 2H5a2 2 0 01-2-2V4zm0 0h18M5 8h14M5 12h14M5 16h14" />
                </svg>
                Filtrar
            </button>
        </form>

        {{-- Botón para Ver Libro Diario (inicio del flujo de reportes) --}}
        <a href="{{ route('contabilidad.libroDiario', request()->query()) }}"
            class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Ver Libro Diario
        </a>
    </div>

    {{-- Tabla de Asientos --}}
    <div class="mt-8">
        <h3 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
            Últimos Asientos Registrados
            @if(request('fecha_inicio') && request('fecha_fin'))
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    (Filtrado desde {{ \Carbon\Carbon::parse(request('fecha_inicio'))->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse(request('fecha_fin'))->format('d/m/Y') }})
                </span>
            @endif
        </h3>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Debe Total</th>
                            <th class="px-4 py-3">Haber Total</th>
                            <th class="px-4 py-3">Eliminar Asiento</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-gray-700 dark:text-gray-400">
                        @forelse($asientos as $asiento)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm">{{ $asiento->fecha }}</td>
                                <td class="px-4 py-3 text-sm">{{ $asiento->descripcion }}</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    {{ number_format($asiento->detalles->sum('debe'), 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    {{ number_format($asiento->detalles->sum('haber'), 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-4 text-sm">
                                        {{-- Botón de Eliminar Asiento --}}
                                        <form action="{{ route('contabilidad.destroy', $asiento->id_asiento) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este asiento? Esta acción es irreversible y eliminará también todos sus detalles.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm1 3a1 1 0 100 2h4a1 1 0 100-2H8z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No hay asientos registrados para el rango de fechas seleccionado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                {{ $asientos->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>

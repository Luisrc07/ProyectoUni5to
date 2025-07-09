<x-layouts.app>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Módulo Contable
    </h2>

    {{-- Alpine.js data para controlar la visibilidad de los modales --}}
    <div x-data="{ isModalOpen: false, isAccountModalOpen: false }">
        {{-- Contenedor de Botones y Filtros --}}
        <div class="flex items-end justify-between mb-6">

            <div class="flex gap-4"> {{-- Agrupar botones de acción --}}
                {{-- 1. Botón para Abrir Modal de Asiento --}}
                <x-button @click="isModalOpen = true" type="button">
                    Registrar Asiento Manual
                </x-button>

                {{-- NUEVO: Botón para Abrir Modal de Cuenta Contable --}}
                <x-button @click="isAccountModalOpen = true" type="button" class="bg-green-600 hover:bg-green-700 active:bg-green-600 focus:shadow-outline-green">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nueva Cuenta Contable
                </x-button>
            </div>

            {{-- 2. Placeholder para filtros (mantener el espacio si es necesario) --}}
            <div></div>

            {{-- 3. Botón "Generar Reporte" (sin funcionalidad por ahora) --}}
            <a href="#"
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                Generar Reporte
            </a>
        </div>

        {{-- Modal para Registrar Asiento Contable --}}
        <x-create-modal
            modal_title="Registrar Asiento Contable"
            form_action="{{ route('contabilidad.store') }}"
            x-show="isModalOpen" {{-- Pasa la variable x-show --}}
        >
        {{-- Pasamos 'movimientoCuentas' que son las cuentas hoja para los detalles del asiento --}}
        @include('components.contabilidad.create', ['cuentas' => $movimientoCuentas])
        </x-create-modal>

        {{-- NUEVO: Modal para Crear Cuenta Contable --}}
        <x-create-modal
            modal_title="Crear Nueva Cuenta Contable"
            form_action="{{ route('contabilidad.cuentas.store') }}"
            x-show="isAccountModalOpen" {{-- Pasa la variable x-show --}}
        >
        {{-- Pasamos 'todasCuentas' para que el usuario pueda seleccionar una cuenta padre --}}
        @include('components.contabilidad.create-cuenta', ['cuentas' => $todasCuentas])
        </x-create-modal>
    </div>

    {{-- AQUI IRÍA LA TABLA DE ASIENTOS EXISTENTES O CUALQUIER OTRO CONTENIDO PRINCIPAL DEL PANEL --}}
    <div class="mt-8">
        <h3 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">Últimos Asientos Registrados</h3>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Debe Total</th>
                            <th class="px-4 py-3">Haber Total</th>
                            <th class="px-4 py-3">Acciones</th>
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
                                        <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.38-2.827-2.828z"></path>
                                            </svg>
                                        </button>
                                        <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm1 3a1 1 0 100 2h4a1 1 0 100-2H8z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No hay asientos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                {{ $asientos->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
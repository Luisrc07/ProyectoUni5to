<x-layouts.app>
    {{-- Encabezado del Reporte --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Reporte - Libro Diario 📖
            @if(request('fecha_inicio') && request('fecha_fin'))
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    (Desde {{ \Carbon\Carbon::parse(request('fecha_inicio'))->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse(request('fecha_fin'))->format('d/m/Y') }})
                </span>
            @endif
        </h2>
        <div class="flex flex-col space-y-2">
            {{-- Botón para Imprimir PDF del Libro Diario --}}
            <a href="{{ route('contabilidad.libroDiarioPDF', request()->query()) }}"
                target="_blank" {{-- Abrir en nueva pestaña para el PDF --}}
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-700 border border-transparent rounded-lg active:bg-gray-700 hover:bg-gray-800 focus:outline-none focus:shadow-outline-gray flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm-3-14V5a2 2 0 012-2h4a2 2 0 012 2v3h-1" />
                </svg>
                Imprimir PDF Libro Diario
            </a>

            {{-- NUEVO BOTÓN: Enlace a la vista HTML del Libro Mayor --}}
            <a href="{{ route('contabilidad.libroMayor', request()->query()) }}"
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Libro Mayor
            </a>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Ref.</th>
                        <th class="px-4 py-3">Cuenta</th>
                        <th class="px-4 py-3 text-right">Debe</th>
                        <th class="px-4 py-3 text-right">Haber</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-gray-700 dark:text-gray-400">
                    @forelse($asientos as $asiento)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">{{ $asiento->fecha }}</td>
                            <td class="px-4 py-3 text-sm">{{ $asiento->descripcion }}</td>
                            {{-- CORRECCIÓN: Usar $loop->index para la numeración del asiento --}}
                            <td class="px-4 py-3 text-sm text-center">A-{{ $loop->index + 1 }}</td>
                            <td class="px-4 py-3 text-sm"></td> {{-- Celda vacía para la cuenta principal del asiento --}}
                            <td class="px-4 py-3 text-sm text-right"></td>
                            <td class="px-4 py-3 text-sm text-right"></td>
                        </tr>
                        @foreach($asiento->detalles as $detalle)
                            <tr class="text-gray-700 dark:text-gray-400 {{ $loop->last ? 'border-b-2 border-gray-200 dark:border-gray-700' : '' }}">
                                <td class="px-4 py-3 text-sm"></td> {{-- Celda vacía para la fecha del detalle --}}
                                <td class="px-4 py-3 text-sm pl-8">{{ $detalle->descripcion_linea ?? $detalle->cuentaContable->nombre }}</td>
                                <td class="px-4 py-3 text-sm text-center">C-{{ $accountReferences[$detalle->id_cuenta] ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $detalle->cuentaContable->nombre ?? 'Cuenta Desconocida' }}</td>
                                <td class="px-4 py-3 text-sm text-right">{{ number_format($detalle->debe, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-right">{{ number_format($detalle->haber, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="text-sm font-semibold tracking-wide text-left text-gray-800 uppercase bg-gray-100 dark:bg-gray-900 dark:text-gray-300">
                            <td class="px-4 py-3" colspan="4">Total Asiento</td>
                            <td class="px-4 py-3 text-right">{{ number_format($asiento->detalles->sum('debe'), 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($asiento->detalles->sum('haber'), 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No hay asientos registrados para el rango de fechas seleccionado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>

<x-layouts.app>
    {{-- Encabezado del Reporte --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Reporte - Libro Mayor 📚
        </h2>
        <div>
            {{-- Botón para Imprimir PDF del Libro Mayor (no funcional aún) --}}
            <a href="{{ route('contabilidad.libroMayorPDF') }}"
                target="_blank" {{-- Abrir en nueva pestaña para el PDF --}}
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-700 border border-transparent rounded-lg active:bg-gray-700 hover:bg-gray-800 focus:outline-none focus:shadow-outline-gray flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm-3-14V5a2 2 0 012-2h4a2 2 0 012 2v3h-1" />
                </svg>
                Imprimir PDF Libro Mayor
            </a>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            @forelse($sortedLibroMayorData as $cuentaMayor)
                <div class="mb-8 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        {{ $cuentaMayor['codigo'] }} - {{ $cuentaMayor['nombre'] }} (M-{{ $cuentaMayor['referencia_cuenta'] }})
                    </h3>

                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3" style="width: 15%;">Fecha</th>
                                <th class="px-4 py-3" style="width: 35%;">Concepto</th>
                                <th class="px-4 py-3" style="width: 10%;">Ref.</th> {{-- Cambiado y movido --}}
                                <th class="px-4 py-3 text-right" style="width: 15%;">Debe</th>
                                <th class="px-4 py-3 text-right" style="width: 15%;">Haber</th>
                                <th class="px-4 py-3 text-right" style="width: 10%;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-gray-700 dark:text-gray-400">
                            @forelse($cuentaMayor['movimientos'] as $movimiento)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($movimiento['fecha'])->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $movimiento['descripcion_asiento'] }}</td>
                                    <td class="px-4 py-3 text-sm">D - {{ $movimiento['asiento_ref_diario'] }}</td> {{-- Usando la referencia secuencial --}}
                                    <td class="px-4 py-3 text-sm text-right">{{ number_format($movimiento['debe'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm text-right">{{ number_format($movimiento['haber'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm text-right">{{ number_format($movimiento['balance_acumulado'], 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No hay movimientos para esta cuenta en el rango de fechas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="text-sm font-extrabold tracking-wide text-left text-gray-800 uppercase border-t dark:border-gray-700 bg-gray-100 dark:text-gray-300 dark:bg-gray-900">
                                {{-- Colspan 3 para "SUMAS TOTALES" (Fecha, Concepto, Ref.) --}}
                                <th class="px-4 py-3" colspan="3">SUMAS TOTALES</th>
                                {{-- Totales de Debe y Haber --}}
                                <th class="px-4 py-3 text-right">{{ number_format($cuentaMayor['total_debe_cuenta'] ?? 0, 2, ',', '.') }}</th>
                                <th class="px-4 py-3 text-right">{{ number_format($cuentaMayor['total_haber_cuenta'] ?? 0, 2, ',', '.') }}</th>
                                {{-- Saldo Final --}}
                                <th class="px-4 py-3 text-right">{{ number_format(end($cuentaMayor['movimientos'])['balance_acumulado'] ?? 0, 2, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @empty
                <p class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No hay cuentas con movimientos para mostrar en el Libro Mayor.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>

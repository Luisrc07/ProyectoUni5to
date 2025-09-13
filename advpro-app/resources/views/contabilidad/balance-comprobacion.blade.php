<x-layouts.app>
    {{-- Encabezado del Reporte --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Reporte - Balance de Comprobación 📊
        </h2>
        <div>
            {{-- Botón para Imprimir PDF del Balance de Comprobación (no funcional aún) --}}
            <a href="{{ route('contabilidad.balanceComprobacionPDF', request()->query()) }}"
                target="_blank" {{-- Abrir en nueva pestaña para el PDF --}}
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-700 border border-transparent rounded-lg active:bg-gray-700 hover:bg-gray-800 focus:outline-none focus:shadow-outline-gray flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm-3-14V5a2 2 0 012-2h4a2 2 0 012 2v3h-1" />
                </svg>
                Imprimir PDF Balance
            </a>
        </div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th rowspan="2" class="px-4 py-3 border-r dark:border-gray-700">Cuenta</th>
                        <th colspan="2" class="px-4 py-3 text-center border-r dark:border-gray-700">Movimientos</th>
                        <th colspan="2" class="px-4 py-3 text-center">Saldo</th>
                    </tr>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3 text-right">Debe</th>
                        <th class="px-4 py-3 text-right border-r dark:border-gray-700">Haber</th>
                        <th class="px-4 py-3 text-right">Debe</th>
                        <th class="px-4 py-3 text-right">Haber</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-gray-700 dark:text-gray-400">
                    @forelse($balanceData as $cuenta)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $cuenta['nombre'] }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ number_format($cuenta['total_movimientos_debe'], 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right border-r dark:border-gray-700">{{ number_format($cuenta['total_movimientos_haber'], 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ number_format($cuenta['saldo_final_debe'], 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ number_format($cuenta['saldo_final_haber'], 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">No hay datos para el Balance de Comprobación.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="text-sm font-extrabold tracking-wide text-left text-gray-800 uppercase border-t dark:border-gray-700 bg-gray-100 dark:text-gray-300 dark:bg-gray-900">
                        <th class="px-4 py-4">Sumas Iguales</th>
                        <th class="px-4 py-4 text-right">{{ number_format($granTotalMovimientosDebe, 2, ',', '.') }}</th>
                        <th class="px-4 py-4 text-right border-r dark:border-gray-700">{{ number_format($granTotalMovimientosHaber, 2, ',', '.') }}</th>
                        <th class="px-4 py-4 text-right">{{ number_format($granTotalSaldoDebe, 2, ',', '.') }}</th>
                        <th class="px-4 py-4 text-right">{{ number_format($granTotalSaldoHaber, 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-layouts.app>

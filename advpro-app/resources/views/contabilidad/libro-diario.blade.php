<x-layouts.app>
    {{-- Encabezado del Reporte --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Reporte - Libro Diario 📖
        </h2>
        <div>
            {{-- Botón para Imprimir PDF del Libro Diario --}}
            <a href="{{ route('contabilidad.libroDiarioPDF') }}"
            target="_blank"
                class="h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-700 border border-transparent rounded-lg active:bg-gray-700 hover:bg-gray-800 focus:outline-none focus:shadow-outline-gray flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm-3-14V5a2 2 0 012-2h4a2 2 0 012 2v3h-1" />
                </svg>
                Imprimir PDF Libro Diario
            </a>

            {{--Enlace al Libro Mayor --}}
            <a href="{{ route('contabilidad.libroMayor') }}"
                class="mt-4 h-10 px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex items-center justify-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ver Libro Mayor
            </a>
        </div>
    </div>

    {{-- El resto de tu tabla HTML del Libro Diario permanece sin cambios --}}
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Cuenta</th>
                        <th class="px-4 py-3 text-center">Ref.</th>
                        <th class="px-4 py-3 text-right">Debe</th>
                        <th class="px-4 py-3 text-right">Haber</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-gray-700 dark:text-gray-400">
                    @php
                        $granTotalDebe = 0;
                        $granTotalHaber = 0;
                    @endphp

                    @forelse($asientos as $asiento)
                        {{-- Fila para la cabecera del asiento --}}
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <td class="px-4 py-3 text-sm font-bold">{{ \Carbon\Carbon::parse($asiento->fecha)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm font-semibold" colspan="4"> ——— Asiento #{{ $loop->iteration }} ——— </td>
                        </tr>

                        {{-- Iterar sobre cada detalle del asiento --}}
                        @foreach($asiento->detalles as $detalle)
                            <tr>
                                <td class="px-4 py-3"></td> {{-- Celda de fecha vacía para alinear --}}
                                <td class="px-4 py-3 text-sm">
                                    @if($detalle->cuentaContable)
                                        {{ $detalle->cuentaContable->codigo }} - {{ $detalle->cuentaContable->nombre }}
                                    @else
                                        <span class="text-red-500">Cuenta no encontrada</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    @if($detalle->cuentaContable && isset($accountReferences[$detalle->cuentaContable->id_cuenta]))
                                        (M-{{ $accountReferences[$detalle->cuentaContable->id_cuenta] }})
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    {{ number_format($detalle->debe, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    {{ number_format($detalle->haber, 2, ',', '.') }}
                                </td>
                            </tr>
                            @php
                                $granTotalDebe += $detalle->debe;
                                $granTotalHaber += $detalle->haber;
                            @endphp
                        @endforeach
                        {{-- Nueva fila con la descripción del asiento y detalles --}}
                        <tr class="bg-white dark:bg-gray-800"> {{-- Se mantiene el fondo de la tabla --}}
                            <td colspan="5" class="px-4 py-3 text-sm italic text-gray-600 dark:text-gray-400 pl-8">
                                P/R (para registrar) {{ $asiento->descripcion }}
                                @php
                                    $lineDescriptions = $asiento->detalles->pluck('descripcion_linea')
                                        ->filter(function ($desc) {
                                            return !empty($desc);
                                        })
                                        ->implode(', ');
                                @endphp
                                @if(!empty($lineDescriptions))
                                    por {{ $lineDescriptions }}
                                @endif
                            </td>
                        </tr>
                        {{-- Espacio entre asientos, solo si no es el último --}}
                        @if(!$loop->last)
                            <tr><td colspan="5" class="border-b border-gray-200 dark:border-gray-700" style="padding: 4px;"></td></tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                No hay asientos para mostrar. ¡Registra el primero!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                {{-- Pie de tabla con los totales --}}
                <tfoot>
                    <tr class="text-sm font-extrabold tracking-wide text-left text-gray-800 uppercase border-t dark:border-gray-700 bg-gray-100 dark:text-gray-300 dark:bg-gray-900">
                        <th class="px-4 py-4" colspan="3">SUMAS IGUALES</th>
                        <th class="px-4 py-4 text-right">{{ number_format($granTotalDebe, 2, ',', '.') }}</th>
                        <th class="px-4 py-4 text-right">{{ number_format($granTotalHaber, 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-layouts.app>

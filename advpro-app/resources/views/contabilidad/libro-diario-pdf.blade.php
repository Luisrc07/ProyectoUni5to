<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Libro Diario</title>
    <style>
        /* Estilos básicos inspirados en tu ejemplo para consistencia */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            margin: 25px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header-row {
            background-color: #e9e9e9;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            font-size: 8px;
            color: #888;
        }
        .total-row th {
            background-color: #333;
            color: #fff;
            font-size: 11px;
        }
        /* Estilo para la nueva fila de descripción del asiento */
        .asiento-summary-row td {
            font-style: italic;
            padding-left: 20px; /* Indentación para la descripción */
            background-color: #f9f9f9; /* Un ligero fondo para distinguirla */
            color: #555;
        }
    </style>
</head>
<body>

    <div class="footer">
        Reporte Generado el {{ date('d/m/Y H:i:s') }}
    </div>

    <h1>Libro Diario</h1>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Fecha</th>
                <th style="width: 43%;">Cuenta</th> {{-- Se ajusta el ancho aquí --}}
                <th style="width: 7%;" class="text-center">Ref.</th> {{-- Aumentado el ancho de la columna Ref. --}}
                <th style="width: 20%;" class="text-right">Debe</th>
                <th style="width: 20%;" class="text-right">Haber</th>
            </tr>
        </thead>
        <tbody>
            @php
                $granTotalDebe = 0;
                $granTotalHaber = 0;
            @endphp

            @forelse($asientos as $asiento)
                {{-- Fila para la cabecera del asiento --}}
                <tr class="header-row">
                    <td>{{ \Carbon\Carbon::parse($asiento->fecha)->format('d/m/Y') }}</td>
                    {{-- Se ajusta el colspan y se usa $loop->iteration para el número incremental --}}
                    <td colspan="4" class="text-center"> ——— Asiento #{{ $loop->iteration }} ——— </td>
                </tr>

                {{-- Iterar sobre cada detalle del asiento --}}
                @foreach($asiento->detalles as $detalle)
                    <tr>
                        <td></td> {{-- Celda de fecha vacía para alinear --}}
                        <td>
                            @if($detalle->cuentaContable)
                                {{ $detalle->cuentaContable->codigo }} - {{ $detalle->cuentaContable->nombre }}
                            @else
                                <span style="color:red;">Cuenta no encontrada</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($detalle->cuentaContable && isset($accountReferences[$detalle->cuentaContable->id_cuenta]))
                                M-{{$accountReferences[$detalle->cuentaContable->id_cuenta] }} {{-- Se eliminan los paréntesis --}}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="text-right">
                            {{ number_format($detalle->debe, 2, ',', '.') }}
                        </td>
                        <td class="text-right">
                            {{ number_format($detalle->haber, 2, ',', '.') }}
                        </td>
                    </tr>
                    @php
                        $granTotalDebe += $detalle->debe;
                        $granTotalHaber += $detalle->haber;
                    @endphp
                @endforeach

                {{-- Nueva fila con la descripción del asiento y detalles --}}
                <tr class="asiento-summary-row">
                    <td colspan="5">
                        P/R (para registrar) {{ $asiento->descripcion }}
                        @php
                            // Recolectar descripciones de líneas no vacías y unirlas
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
                    <tr><td colspan="5" style="border:none; padding: 4px;"></td></tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">No hay asientos para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                {{-- Se ajusta el colspan para "SUMAS IGUALES" --}}
                <th colspan="3">SUMAS IGUALES</th>
                <th class="text-right">{{ number_format($granTotalDebe, 2, ',', '.') }}</th>
                <th class="text-right">{{ number_format($granTotalHaber, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>

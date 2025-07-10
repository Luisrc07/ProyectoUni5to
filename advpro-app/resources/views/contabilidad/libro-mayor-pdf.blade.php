<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Libro Mayor</title>
    <style>
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
        h2 {
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
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
            font-size: 10px;
        }
        .account-header {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: left;
            padding: 8px;
        }
        /* Para que cada cuenta inicie en una nueva página en el PDF */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <div class="footer">
        Reporte Generado el {{ date('d/m/Y H:i:s') }}
    </div>

    <h1>Libro Mayor</h1>

    @forelse($sortedLibroMayorData as $cuentaMayor)
        {{-- Salto de página para cada nueva cuenta, excepto la primera --}}
        @if(!$loop->first)
            <div class="page-break"></div>
        @endif

        <h2 class="account-header">
            {{ $cuentaMayor['codigo'] }} - {{ $cuentaMayor['nombre'] }} (M-{{ $cuentaMayor['referencia_cuenta'] }})
        </h2>

        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Fecha</th>
                    <th style="width: 35%;">Concepto</th>
                    <th style="width: 10%;">Ref.</th>
                    <th style="width: 15%;" class="text-right">Debe</th>
                    <th style="width: 15%;" class="text-right">Haber</th>
                    <th style="width: 10%;" class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuentaMayor['movimientos'] as $movimiento)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($movimiento['fecha'])->format('d/m/Y') }}</td>
                        <td>{{ $movimiento['descripcion_asiento'] }}</td>
                        <td>D - {{ $movimiento['asiento_ref_diario'] }}</td>
                        <td class="text-right">{{ number_format($movimiento['debe'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($movimiento['haber'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($movimiento['balance_acumulado'], 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay movimientos para esta cuenta en el rango de fechas.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="3">SUMAS TOTALES</th>
                    <th class="text-right">{{ number_format($cuentaMayor['total_debe_cuenta'] ?? 0, 2, ',', '.') }}</th>
                    <th class="text-right">{{ number_format($cuentaMayor['total_haber_cuenta'] ?? 0, 2, ',', '.') }}</th>
                    <th class="text-right">{{ number_format(end($cuentaMayor['movimientos'])['balance_acumulado'] ?? 0, 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    @empty
        <p class="text-center">No hay cuentas con movimientos para mostrar en el Libro Mayor.</p>
    @endforelse

</body>
</html>

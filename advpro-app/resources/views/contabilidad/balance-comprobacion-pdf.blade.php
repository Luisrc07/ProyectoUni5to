<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - Balance de Comprobación</title>
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
        /* Estilos específicos para el Balance de Comprobación */
        .balance-header th {
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .balance-header .sub-header th {
            font-weight: normal;
        }
    </style>
</head>
<body>

    <div class="footer">
        Reporte Generado el {{ date('d/m/Y H:i:s') }}
    </div>

    <h1>Balance de Comprobación</h1>
    <h2 style="text-align: center; font-size: 12px; margin-bottom: 15px;">Compañía Orizaba, S.A.</h2> {{-- Ejemplo de nombre de compañía --}}
    <h2 style="text-align: center; font-size: 12px; margin-bottom: 15px;">Balance de comprobación al {{ date('d') }} de {{ date('F') }}</h2> {{-- Ejemplo de fecha actual --}}


    <table>
        <thead>
            <tr class="balance-header">
                <th rowspan="2" style="width: 25%; border-right: 1px solid #ccc;">Cuenta</th>
                <th colspan="2" class="text-center" style="width: 35%; border-right: 1px solid #ccc;">Movimientos</th>
                <th colspan="2" class="text-center" style="width: 40%;">Saldo</th>
            </tr>
            <tr class="balance-header sub-header">
                <th style="width: 17.5%;" class="text-right">Debe</th>
                <th style="width: 17.5%; border-right: 1px solid #ccc;" class="text-right">Haber</th>
                <th style="width: 20%;" class="text-right">Debe</th>
                <th style="width: 20%;" class="text-right">Haber</th>
            </tr>
        </thead>
        <tbody>
            @forelse($balanceData as $cuenta)
                <tr>
                    <td>{{ $cuenta['nombre'] }}</td>
                    <td class="text-right">{{ number_format($cuenta['total_movimientos_debe'], 2, ',', '.') }}</td>
                    <td class="text-right" style="border-right: 1px solid #ccc;">{{ number_format($cuenta['total_movimientos_haber'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($cuenta['saldo_final_debe'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($cuenta['saldo_final_haber'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">No hay datos para el Balance de Comprobación.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th>Sumas Iguales</th>
                <th class="text-right">{{ number_format($granTotalMovimientosDebe, 2, ',', '.') }}</th>
                <th class="text-right" style="border-right: 1px solid #ccc;">{{ number_format($granTotalMovimientosHaber, 2, ',', '.') }}</th>
                <th class="text-right">{{ number_format($granTotalSaldoDebe, 2, ',', '.') }}</th>
                <th class="text-right">{{ number_format($granTotalSaldoHaber, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

</body>
</html>

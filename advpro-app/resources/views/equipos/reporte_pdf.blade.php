<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Equipos</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Usar una fuente que soporte UTF-8 */
            font-size: 10px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>

    <h1>Reporte de Equipos</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Marca</th>
                <th>Tipo de Equipo</th>
                <th>Estado</th>
                <th>Ubicación</th>
                <th>Responsable</th>
                <th>Valor</th> {{-- ¡Columna de Valor Añadida! --}}
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($equipos_para_reporte as $equipo)
                <tr>
                    <td>{{ $equipo->id }}</td>
                    <td>{{ $equipo->nombre }}</td>
                    <td>{{ $equipo->descripcion }}</td>
                    <td>{{ $equipo->marca }}</td>
                    <td>{{ $equipo->tipo_equipo }}</td>
                    <td>{{ $equipo->estado }}</td>
                    <td>{{ $equipo->ubicacion }}</td>
                    <td>{{ $equipo->personal->nombre ?? 'N/A' }}</td>
                    <td>${{ number_format($equipo->valor, 2, ',', '.') }}</td> {{-- Formato de moneda --}}
                    <td>{{ $equipo->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No hay equipos que coincidan con los filtros.</td> {{-- Colspan ajustado a 10 --}}
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado por AudioVisual Pro - {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>
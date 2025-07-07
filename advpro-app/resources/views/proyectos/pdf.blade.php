<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proyectos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        h1 {
            text-align: center;
            color: #4a4a4a;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 9px; /* Tamaño de fuente más pequeño para que quepa más contenido */
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }
        .total-row {
            font-weight: bold;
            background-color: #e6e6e6;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            background-color: #e9e9e9;
            padding: 5px;
        }
        .resource-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .resource-list li {
            font-size: 8px;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

    <h1>Reporte de Proyectos</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Presupuesto</th>
                <th>Lugar</th>
                <th>Responsable</th>
                <th>Personal Asignado</th>
                <th>Equipos Asignados</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proyectos as $proyecto)
                <tr>
                    <td>{{ $proyecto->id }}</td>
                    <td>{{ $proyecto->nombre }}</td>
                    <td>{{ $proyecto->estado }}</td>
                    <td>{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</td>
                    <td>{{ $proyecto->fecha_fin ? \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') : 'N/A' }}</td>
                    <td>${{ number_format($proyecto->presupuesto, 2, ',', '.') }}</td>
                    <td>{{ $proyecto->lugar }}</td>
                    <td>{{ $proyecto->responsable->nombre ?? 'N/A' }}</td>
                    <td>
                        @if ($proyecto->personalAsignado->isNotEmpty())
                            <ul class="resource-list">
                                @foreach ($proyecto->personalAsignado as $personal)
                                    <li>- {{ $personal->nombre }} ({{ \Carbon\Carbon::parse($personal->pivot->fecha_asignacion)->format('d/m/Y') }} - {{ $personal->pivot->fecha_fin_asignacion ? \Carbon\Carbon::parse($personal->pivot->fecha_fin_asignacion)->format('d/m/Y') : 'Indef.' }})</li>
                                @endforeach
                            </ul>
                        @else
                            Ninguno
                        @endif
                    </td>
                    <td>
                        @if ($proyecto->equiposAsignados->isNotEmpty())
                            <ul class="resource-list">
                                @foreach ($proyecto->equiposAsignados as $equipo)
                                    <li>- {{ $equipo->nombre }} (x{{ $equipo->pivot->cantidad }})</li>
                                @endforeach
                            </ul>
                        @else
                            Ninguno
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No hay proyectos que coincidan con los filtros.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5">Total de Proyectos:</td>
                <td>{{ count($proyectos) }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>

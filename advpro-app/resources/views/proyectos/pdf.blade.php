<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proyectos</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif; /* Fuente Times New Roman */
            font-size: 12px; /* Letra ligeramente más grande */
            margin: 20px;
            background-color: #fff; /* Fondo blanco para el PDF */
            color: #000; /* Color de texto negro */
        }
        h1 {
            text-align: center;
            font-size: 30px; /* Título principal más grande */
            margin-bottom: 10px; /* Espacio más ajustado */
            color: #000; /* Color de texto negro para el título */
            padding-bottom: 5px; /* Espacio para la línea */
            border-bottom: 2px solid #8A2BE2; /* Raya morada debajo del título de la empresa */
            display: inline-block; /* Para que la línea se ajuste al texto */
            width: 100%; /* Para que la línea ocupe todo el ancho */
            box-sizing: border-box; /* Incluye padding y border en el ancho */
        }
        h2 {
            text-align: center;
            font-size: 20px; /* Título del reporte */
            margin-top: 20px;
            margin-bottom: 20px;
            color: #000; /* Color de texto negro para el título */
            padding-bottom: 5px; /* Espacio para la línea */
            border-bottom: 1px solid #8A2BE2; /* Raya morada debajo del título del reporte */
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px; /* Bordes un poco más redondeados para la tabla */
            overflow: hidden; /* Asegura que los bordes redondeados se apliquen a las celdas */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sutil sombra para elegancia en fondo claro */
        }
        th, td {
            border: 1px solid #8A2BE2; /* Líneas de la tabla en morado */
            padding: 10px; /* Padding un poco más grande para elegancia */
            text-align: left;
            color: #000; /* Color de texto negro para celdas */
        }
        th {
            background-color: #f2f2f2; /* Un gris claro para los encabezados */
            color: #000; /* Color de texto negro para los encabezados */
            font-weight: bold;
        }
        tr:nth-child(even) { /* Filas alternas para mejor legibilidad */
            background-color: #f9f9f9; /* Un blanco muy claro para filas pares */
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
            font-size: 10px;
            color: #666; /* Un gris oscuro para el texto del footer */
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

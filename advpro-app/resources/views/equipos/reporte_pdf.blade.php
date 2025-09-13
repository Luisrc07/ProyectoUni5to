<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Equipos</title>
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

    <h1>AudioVisualPro</h1> {{-- Título principal "AudioVisualPro" --}}
    <h2>Reporte de Equipos</h2> {{-- Título del reporte --}}
    
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
                <th>Valor</th>
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
                    <td>{{ $equipo->responsableStaff->nombre ?? 'N/A' }}</td>
                    <td>${{ number_format($equipo->valor, 2, ',', '.') }}</td>
                    <td>{{ $equipo->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; color: #000;">No hay equipos que coincidan con los filtros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado por AudioVisual Pro - {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>

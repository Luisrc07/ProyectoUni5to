<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Clientes</title>
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
    <div class="header">
        Generado el: {{ date('d/m/Y H:i') }}
    </div>

    <h1>Reporte de Clientes</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                {{-- Columnas de Contratos y Proyectos eliminadas --}}
            </tr>
        </thead>
        <tbody>
            @forelse($clientes_para_reporte as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->tipo_documento }}-{{ $cliente->documento }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->direccion }}</td>
                    {{-- Lógica para Contratos y Proyectos eliminada --}}
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No hay clientes que coincidan con los filtros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Página <span class="pagenum"></span>
    </div>
</body>
</html>
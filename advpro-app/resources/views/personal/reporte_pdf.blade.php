<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Personal</title>
    <style>
        /* Estilos generales para el PDF */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
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
            vertical-align: top; /* Asegura que el contenido esté alineado en la parte superior */
        }
        th {
            background-color: #f2f2f2;
            color: #555;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-top: 30px;
        }
        /* Estilos para el estado */
        .estado-activo {
            color: green;
            font-weight: bold;
        }
        .estado-inactivo {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Reporte de Personal Administrativo</h1>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Cargo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($staff as $personal)
                <tr>
                    <td>{{ $personal->nombre }}</td>
                    <td>{{ $personal->tipo_documento }} - {{ $personal->documento }}</td>
                    <td>{{ $personal->email ?? 'N/A' }}</td>
                    <td>{{ $personal->telefono ?? 'N/A' }}</td>
                    <td>{{ $personal->direccion ?? 'N/A' }}</td>
                    <td>{{ $personal->cargo ?? 'N/A' }}</td>
                    <td>
                        <span class="{{ $personal->estado == 'Activo' ? 'estado-activo' : 'estado-inactivo' }}">
                            {{ $personal->estado }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No hay personal para mostrar en este reporte.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Clientes</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            margin: 40px;
        }
        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
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
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header, .footer {
            width: 100%;
            position: fixed;
            text-align: center;
        }
        .header {
            top: -30px;
            left: 0;
            right: 0;
            font-size: 12px;
            color: #666;
        }
        .footer {
            bottom: -30px;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #666;
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato - {{ $contrato->serial }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .section {
            margin-bottom: 20px;
        }
        .signatures {
            margin-top: 50px;
            display: table; /* Para alinear horizontalmente con display:table-cell */
            width: 100%;
        }
        .signature-col {
            display: table-cell; /* Para crear columnas */
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>CONTRATO DE PRESTACIÓN DE SERVICIOS AUDIOVISUALES</h2>

        <div class="section">
            <p>
                Entre: <strong>AudiovisualPro C.A.</strong>, RIF J-12345678-9, con domicilio en Barquisimeto, 
                Estado Lara, representada en este acto por su Director General, quien en lo sucesivo se denominará 
                "El Prestador", y <strong>{{ $contrato->cliente->nombre }}</strong>, 
                titular de la cédula de identidad N° {{ $contrato->cliente->documento ?? '[C.I.]' }},
                con domicilio en {{ $contrato->cliente->direccion ?? '[Dirección]' }},
                quien en lo sucesivo se denominará "El Cliente",
            </p>
            
            <p>
                se acuerda lo siguiente:
            </p>
        </div>

        <div class="section">
            <h3>1. Objeto del Contrato</h3>
            <p>
                El Prestador se compromete a realizar un proyecto audiovisual titulado "<strong>{{ $contrato->proyecto->nombre }}</strong>", que incluye:
            </p>
            <ul>
                <li>Preproducción (guion, planificación, casting)</li>
                <li>Producción (grabación en locaciones acordadas)</li>
                <li>Postproducción (edición, efectos, sonido, entrega final)</li>
            </ul>
        </div>

        <div class="section">
            <h3>2. Plazo de Ejecución</h3>
            <p>
                El proyecto se ejecutará entre el 
                <strong>{{ $contrato->fecha_inicio_proyecto?->format('d/m/Y') ?? 'Fecha de Inicio No Especificada' }}</strong> 
                y el 
                <strong>{{ $contrato->fecha_fin_proyecto?->format('d/m/Y') ?? 'Fecha de Fin No Especificada' }}</strong>, 
                salvo causas de fuerza mayor debidamente justificadas.
            </p>
        </div>

        <div class="section">
            <h3>3. Honorarios y Forma de Pago</h3>
            <p>
                El Cliente pagará al Prestador la suma de 
                {{-- Ahora el campo 'costo' del contrato ya es el costo final --}}
                <strong>{{ number_format($contrato->costo, 2, ',', '.') }} ({{ \App\Helpers\NumberToWords::convert($contrato->costo) }} BOLÍVARES)</strong>, 
                distribuidos de la siguiente manera:
            </p>
            <ul>
                <li>50% al inicio del proyecto</li>
                <li>50% contra entrega del producto final</li>
            </ul>
        </div>

        <div class="section">
            <h3>4. Derechos de Autor y Uso</h3>
            <p>El Prestador conserva los derechos morales sobre la obra.</p>
            <p>El Cliente adquiere los derechos de uso y difusión del material para los fines acordados.</p>
            <p>Cualquier uso adicional requerirá autorización escrita del Prestador.</p>
        </div>

        <div class="section">
            <h3>5. Confidencialidad</h3>
            <p>Ambas partes se comprometen a mantener la confidencialidad sobre cualquier información sensible relacionada con el proyecto.</p>
        </div>

        <div class="section">
            <h3>6. Resolución de Conflictos</h3>
            <p>Cualquier controversia será resuelta de forma amistosa. En caso contrario, se someterá a la jurisdicción de los tribunales de Barquisimeto, Estado Lara.</p>
        </div>

        <div class="signatures">
            <div class="signature-col">
                <p>Por AudiovisualPro C.A.</p>
                <br><br>
                <p>__________________________</p>
                <p><strong>Nombre:</strong> __________________________</p>
                <p><strong>Cargo:</strong> Director General</p>
            </div>
            <div class="signature-col">
                <p>Por El Cliente</p>
                <br><br>
                <p>__________________________</p>
                <p><strong>Nombre:</strong> {{ $contrato->cliente->nombre }}</p>
                <p><strong>C.I.:</strong> {{ $contrato->cliente->documento ?? '________________' }}</p>
                <p>Serial Unico De Contrato : {{ $contrato->serial }} </p>
            </div>
        </div>
    </div>
</body>
</html>
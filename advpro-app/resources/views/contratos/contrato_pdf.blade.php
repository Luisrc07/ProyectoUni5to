<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato - {{ $contrato->serial }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 11pt;
            line-height: 1.8;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            padding: 40px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
        }
        h1, h2, h3 {
            text-align: center;
            color: #1a237e;
            margin-bottom: 25px;
            font-weight: 700;
        }
        h2 {
            font-size: 24pt;
            border-bottom: 2px solid #1a237e;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        h3 {
    font-size: 16pt;
    color: #3f51b5;
    margin-top: 50px;
    margin-bottom: 20px; 
    text-align: left;
}

        .section {
            margin-bottom: 25px;
            padding: 0 10px;
        }
        p {
            margin-bottom: 10px;
            text-align: justify;
        }
        ul {
            list-style-type: disc;
            margin-left: 30px;
            margin-bottom: 15px;
        }
        ul li {
            margin-bottom: 5px;
        }
        .signature-col {
    width: 48%;
    min-width: 200px;
    text-align: center;
    vertical-align: top;
    padding: 10px;
    box-sizing: border-box;
}

.signatures {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px; /* Opcional: espacio entre las dos firmas */
    flex-wrap: nowrap;
}
        .signature-col p {
            margin-bottom: 5px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 60px auto 5px auto;
            width: 80%;
        }
        .footer-info {
            margin-top: 40px;
            text-align: right;
            font-size: 9pt;
            color: #777;
        }

        /* Print specific styles to ensure good PDF output */
        @media print {
            body {
                background-color: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .container {
                box-shadow: none;
                border: none;
                margin: 0;
                width: 100%;
                max-width: none;
                padding: 0;
            }
            .section {
                padding: 0 20px;
            }
            h2 {
                margin-top: 30px;
            }
            .signatures {
                margin-top: 80px;
                page-break-before: auto;
                justify-content: space-between; /* More precise spacing for print */
            }
            .signature-col {
                width: 48%; /* Maintain widths for print */
            }
            .footer-info {
                margin-top: 60px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>CONTRATO DE PRESTACIÓN DE SERVICIOS AUDIOVISUALES</h2>

        <div class="section">
            <p>
                Entre: <strong>AudiovisualPro C.A.</strong>, RIF J-12345678-9, con domicilio en Barquisimeto, 
                Estado Lara, Venezuela, representada en este acto por su Director General, quien en lo sucesivo se denominará 
                "El Prestador", y <strong>{{ $contrato->cliente->nombre }}</strong>, 
                titular de la cédula de identidad N° <strong>{{ $contrato->cliente->documento ?? '[C.I. no especificada]' }}</strong>,
                con domicilio en <strong>{{ $contrato->cliente->direccion ?? '[Dirección no especificada]' }}</strong>,
                quien en lo sucesivo se denominará "El Cliente",
            </p>
            <p>
                se ha acordado celebrar el presente Contrato de Prestación de Servicios Audiovisuales, el cual se regirá por las siguientes cláusulas:
            </p>
        </div>

        <div class="section">
            <h3>1. Objeto del Contrato</h3>
            <p>
                El Prestador se compromete a realizar un proyecto audiovisual denominado "<strong>{{ $contrato->proyecto->nombre }}</strong>", el cual incluirá las siguientes fases:
            </p>
            <ul>
                <li><strong>Preproducción:</strong> Desarrollo de guion, planificación detallada y proceso de casting si aplica.</li>
                <li><strong>Producción:</strong> Grabación de todo el material audiovisual en las locaciones previamente acordadas.</li>
                <li><strong>Postproducción:</strong> Edición, inclusión de efectos visuales y sonoros, y entrega del producto final en el formato establecido.</li>
            </ul>
        </div>

        <div class="section">
            <h3>2. Plazo de Ejecución</h3>
            <p>
                El proyecto se ejecutará en el período comprendido entre el 
                <strong>{{ $contrato->fecha_contrato?->format('d/m/Y') ?? 'Fecha de Inicio No Especificada' }}</strong> 
                y el 
                <strong>{{ $contrato->fecha_entrega?->format('d/m/Y') ?? 'Fecha de Fin No Especificada' }}</strong>. 
                Cualquier retraso en el plazo de entrega deberá ser notificado y debidamente justificado por causas de fuerza mayor.
            </p>
        </div>

        <div class="section">
            <h3>3. Honorarios y Forma de Pago</h3>
            <p>
                El Cliente se compromete a pagar al Prestador la suma total de 
                <strong>{{ number_format($contrato->costo, 2, ',', '.') }} ({{ \App\Helpers\NumberToWords::convert($contrato->costo) }} BOLÍVARES)</strong>, 
                distribuidos de la siguiente manera:
            </p>
            <ul>
                <li><strong>50%</strong> del monto total al inicio de la ejecución del proyecto.</li>
                <li><strong>50%</strong> restante contra la entrega final y aprobación del producto audiovisual por parte del Cliente.</li>
            </ul>
        </div>

        <div class="section">
            <h3>4. Derechos de Autor y Uso</h3>
            <p>El Prestador conservará los derechos morales sobre la obra audiovisual realizada.</p>
            <p>El Cliente adquirirá los derechos de uso y difusión del material exclusivamente para los fines que han sido acordados y especificados en este contrato.</p>
            <p>Cualquier uso adicional, modificación o distribución del material que exceda lo estipulado en este contrato, requerirá de la autorización escrita previa por parte del Prestador.</p>
        </div>

        <div class="section">
            <h3>5. Confidencialidad</h3>
            <p>Ambas partes, El Prestador y El Cliente, se comprometen a mantener la más estricta confidencialidad sobre cualquier información sensible, datos, procesos o estrategias que sean reveladas o accedidas durante la ejecución de este proyecto.</p>
        </div>

        <div class="section">
            <h3>6. Resolución de Conflictos</h3>
            <p>
                Cualquier controversia o disputa que surja en relación con la interpretación, ejecución o cumplimiento del presente contrato, será resuelta de mutuo acuerdo y de forma amistosa entre las partes. En caso de no llegar a un acuerdo, las partes se someterán a la jurisdicción de los tribunales competentes de la ciudad de Barquisimeto, Estado Lara, Venezuela.
            </p>
        </div>

        <div class="signatures">
            <div class="signature-col">
                <p><strong>Por AudiovisualPro C.A.</strong></p>
                <div class="signature-line"></div>
                <p><strong>Nombre:</strong> __________________________</p>
                <p><strong>Cargo:</strong> Director General</p>
            </div>
            <div class="signature-col">
                <p><strong>Por El Cliente</strong></p>
                <div class="signature-line"></div>
                <p><strong>Nombre:</strong> <strong>{{ $contrato->cliente->nombre }}</strong></p>
                <p><strong>C.I.:</strong> <strong>{{ $contrato->cliente->documento ?? '________________' }}</strong></p>
            </div>
        </div>
        <div class="footer-info">
            <p><strong>Serial Único de Contrato:</strong> <strong>{{ $contrato->serial }}</strong></p>
            <p>Barquisimeto, Estado Lara, Venezuela. {{ \Carbon\Carbon::now()->translatedFormat('d \\de F \\de Y') }}</p>
        </div>
    </div>
</body>
</html>
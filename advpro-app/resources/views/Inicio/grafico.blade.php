<!DOCTYPE html>
<html>
<head>
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #666; }
        img { max-width: 100%; height: auto; display: block; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $titulo }}</h2>
        <p>Generado el: {{ $fecha }}</p>
    </div>
    
    <img src="{{ $image }}">
    
    <div class="footer">
        <p>Sistema de Gestión Contable</p>
    </div>
</body>
</html>
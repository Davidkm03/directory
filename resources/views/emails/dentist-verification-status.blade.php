<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualización de Estado de Verificación</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4A90E2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .status-approved {
            color: #2E7D32;
            font-weight: bold;
        }
        .status-rejected {
            color: #C62828;
            font-weight: bold;
        }
        .status-pending {
            color: #F9A825;
            font-weight: bold;
        }
        .notes {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #4A90E2;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Comunidad Odontológica</h1>
    </div>
    <div class="content">
        <p>Hola {{ $name }},</p>
        
        <p>Queremos informarte que el estado de verificación de tu perfil profesional ha sido actualizado.</p>
        
        <p>El estado actual de tu perfil es: 
            @if($status == 'approved')
                <span class="status-approved">APROBADO</span>
            @elseif($status == 'rejected')
                <span class="status-rejected">RECHAZADO</span>
            @else
                <span class="status-pending">PENDIENTE</span>
            @endif
        </p>

        @if($notes)
            <div class="notes">
                <h3>Notas del equipo de verificación:</h3>
                <p>{{ $notes }}</p>
            </div>
        @endif

        @if($status == 'approved')
            <p>¡Felicidades! Tu perfil es ahora visible en nuestra comunidad. Los pacientes podrán encontrar tu información profesional y contactarte a través de la plataforma.</p>
        @elseif($status == 'rejected')
            <p>Por favor, revisa las notas proporcionadas por nuestro equipo de verificación y realiza las correcciones necesarias. Una vez actualizadas, tu perfil volverá a entrar en proceso de revisión.</p>
        @else
            <p>Tu perfil está siendo revisado por nuestro equipo de verificación. Te notificaremos cuando haya novedades.</p>
        @endif

        <p>Para ver más detalles o realizar cambios, inicia sesión en tu cuenta y visita la sección "Mi Perfil Profesional".</p>
        
        <p>¡Gracias por formar parte de nuestra comunidad!</p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Comunidad Odontológica - Todos los derechos reservados</p>
    </div>
</body>
</html>

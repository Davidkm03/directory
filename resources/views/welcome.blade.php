<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="0;url={{ !file_exists(storage_path('installed')) ? '/install' : '/' }}">
    <title>{{ config('app.name', 'Comunidad Odontológica') }}</title>
</head>
<body>
    <p>Redireccionando...</p>
    <script>
        window.location.href = "{{ !file_exists(storage_path('installed')) ? '/install' : '/' }}";
    </script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CSS Diagnostics</title>
    <!-- Inline styles to ensure functionality -->
    <style>
        .test-box {
            padding: 1rem;
            margin: 1rem;
            background-color: blue;
            color: white;
            border-radius: 0.5rem;
        }
    </style>
    <!-- Cargar directamente el CSS compilado con URL absoluta -->
    <link href="http://127.0.0.1:8001/build/assets/app-D8IWtaEv.css" rel="stylesheet">
</head>
<body>
    <h1>CSS Diagnostics</h1>
    
    <!-- Inline styles box - should always display correctly -->
    <div class="test-box">
        This box uses inline styles and should appear blue with white text.
    </div>
    
    <!-- Tailwind classes box - works only if Tailwind loads -->
    <div class="p-4 m-4 bg-blue-500 text-white rounded-lg">
        If you see this text inside a blue box with white text, Tailwind is working.
    </div>
    
    <hr>
    <div>
        <h3>Debug information:</h3>
        <p>CSS URL: <code>{{ asset('build/assets/app-D8IWtaEv.css') }}</code></p>
        <p>Application URL: <code>{{ config('app.url') }}</code></p>
        <p>Absolute path to CSS: <code>{{ public_path('build/assets/app-D8IWtaEv.css') }}</code></p>
        <p>File exists? <code>{{ file_exists(public_path('build/assets/app-D8IWtaEv.css')) ? 'Yes' : 'No' }}</code></p>
    </div>
</body>
</html>

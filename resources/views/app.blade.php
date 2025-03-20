<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <title inertia>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/js/app.js') <!-- Esto incluye tu archivo JS generado por Vite -->
  <link rel="shortcut icon" href="{{ Vite::asset('resources/images/logoTC.svg') }}" type="image/x-icon">


  @routes
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- <title>Taurus POS</title> -->
</head>
<body class="bg-mono-negro scrollbar-custom">
  @inertia
</body>
</html>

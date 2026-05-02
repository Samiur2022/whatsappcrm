<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Errore') - {{ config('app.name', 'SNS CRM') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100 antialiased">
    <div class="w-full max-w-md mx-auto px-4">
        @yield('content')
    </div>
</body>
</html>
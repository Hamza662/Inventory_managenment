<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $portalSettings->store_name ?? 'Inventory' }} · Agent</title>
    <link rel="icon" href="{{ $portalSettings->faviconUrl() ?? asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ $portalSettings->faviconUrl() ?? asset('favicon.png') }}">
</head>
<body>
    <h1>{{ $portalSettings->store_name ?? 'Inventory' }} · Agent Dashboard</h1>
</body>
</html>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Pet QR Credential')</title>
    <style>
        @page { margin: 28px 32px; }
        body { font-family: Helvetica, Arial, sans-serif; color: #122c4f; }
        .header { border-bottom: 3px solid #122c4f; padding-bottom: 10px; margin-bottom: 22px; }
        .brand { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #607089; font-weight: bold; }
        h1 { margin: 4px 0 0; font-size: 22px; }
        .card { display: table; width: 100%; border: 1px solid #cfd8e6; border-radius: 8px; }
        .qr-side { display: table-cell; width: 220px; text-align: center; vertical-align: middle; padding: 20px; border-right: 1px solid #cfd8e6; background: #f5f8fc; }
        .qr-caption { font-size: 10px; color: #607089; margin-top: 8px; }
        .info-side { display: table-cell; padding: 18px 22px; vertical-align: top; }
        .pet-name { font-size: 20px; font-weight: bold; margin-bottom: 12px; }
        .info-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .info-table td { padding: 5px 0; border-bottom: 1px solid #eef1f5; vertical-align: top; }
        .info-table td.label { color: #607089; font-weight: bold; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; width: 130px; padding-right: 10px; }
        .footer { margin-top: 18px; font-size: 10px; color: #607089; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>

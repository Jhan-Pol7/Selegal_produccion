<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ticket de cita {{ $a->id }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&amp;family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=2">
<style>
@page { size: 80mm auto; margin: 3mm; }
</style>
</head>
<body class="ticket-page">
<main class="ticket-card">
<div class="ticket-brand">
    <img src="/assets/escudo.jpg" alt="Escudo SELEGAL" width="64" height="64">
    <h1>SELEGAL</h1>
    <p>Centro de Conciliación Extrajudicial</p>
    <p>R.D. N.° 364-2026-JUS/DGDPAJ/DCMA</p>
</div>
<div class="ticket-content">
<h2>CONSTANCIA DE CITA</h2>
<dl class="ticket-details">
    <div><dt>Solicitud:</dt> <dd>{{ $a->request_id }}</dd></div>
    <div><dt>Nombre:</dt> <dd>{{ $a->name }}</dd></div>
    <div><dt>Materia:</dt> <dd>{{ $a->matter }}</dd></div>
    <div><dt>Fecha:</dt> <dd>{{ \Carbon\Carbon::parse($a->day)->format('d/m/Y') }}</dd></div>
    <div><dt>Hora:</dt> <dd>{{ $a->time }}</dd></div>
    <div><dt>Estado:</dt> <dd>{{ $a->status }}</dd></div>
</dl>
<div class="ticket-code">
@php
// Code 39: narrow and wide bars, including start/stop characters and quiet zones.
$patterns = ['0'=>'nnnwwnwnn','1'=>'wnnwnnnnw','2'=>'nnwwnnnnw','3'=>'wnwwnnnnn','4'=>'nnnwwnnnw','5'=>'wnnwwnnnn','6'=>'nnwwwnnnn','7'=>'nnnwnnwnw','8'=>'wnnwnnwnn','9'=>'nnwwnnwnn','*'=>'nwnnwnwnn'];
$code = str_pad((string) $a->id, 8, '0', STR_PAD_LEFT);
$x = 10;
$bars = [];
foreach (str_split('*'.$code.'*') as $character) {
    foreach (str_split($patterns[$character]) as $index => $width) {
        $units = $width === 'w' ? 3 : 1;
        if ($index % 2 === 0) $bars[] = [$x, $units];
        $x += $units;
    }
    $x++;
}
$barcodeWidth = $x + 10;
@endphp
<svg class="barcode" viewBox="0 0 {{ $barcodeWidth }} 50" preserveAspectRatio="none" role="img" aria-label="Código de barras de cita {{ $code }}" xmlns="http://www.w3.org/2000/svg" shape-rendering="crispEdges">
@foreach($bars as [$position, $width])<rect x="{{ $position }}" y="0" width="{{ $width }}" height="50" fill="#000"/>@endforeach
</svg>
    <b>{{ $code }}</b>
    <p>Gracias por confiar en SELEGAL</p>
</div>
<button class="btn btn-gold print" type="button" onclick="window.print()">Imprimir ticket</button>
</div>
</main>
</body>
</html>

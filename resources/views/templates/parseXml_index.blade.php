<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parse XML files</title>
</head>
<body>
    @if (isset($data))
    {{ $data['result'] }}
    @endif
    <ul>
        <li><a href="{{ route('parseXmlProducten') }}">Verwerk producten - XML</a></li>
        <li><a href="{{ route('parseXmlKlanten') }}">Verwerk klanten - XML</a></li>
        <li><a href="{{ route('parseXmlVoorraden') }}">Verwerk voorraden - XML</a></li>
        <li><a href="{{ route('parseXmlWmsorders') }}">Verwerk orders vanuit WMS - XML</a></li>
    </ul>
    <p><a href="/">< Terug</a></p>
</body>
</html>
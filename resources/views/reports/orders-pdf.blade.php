{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF export</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
        }
        table tr th, table tr td {
            padding: 15px;
        }
        table tr:nth-of-type(even) td {
            background-color: #EFEFEF;
        }
        table tr:nth-of-type(1) th {
            background-color: #000;
            color: #FFF;
        }
        header {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ asset('statics/hulshoff-logo.png') }}" alt="Hulshoff">
        <div>
            <p>
                Periode: {{ $period }}<br />
                Klant: {{ $client }}<br />
                Gebruiker: {{ $user }}<br />
                Rapportage type: {{ $type }}<br />
            </p>
        </div>
    </header>
@include('reports.orders')
</body>
</html> --}}
@extends('templates.pdf')
@section('pdf_content')
    @include('reports.orders')
@endsection
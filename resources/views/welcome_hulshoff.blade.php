<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #F7FAFC;
            font-family: 'Nunito', sans-serif;
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        div.textWrap {
            width: 100%;
            max-width: 960px;
            margin-top: 20px;
            border: 1px solid #EFEFEF;
            border-radius: 5px;
            background-color: #FFF;
            padding: 20px;
            -webkit-box-shadow: 1px 1px 1px 0px #CCC;
            box-shadow: 1px 1px 1px 0px #CCC;
        }
        div.logoWrap img {
            padding: 10px;
            background-color: #FFE600;
            border-radius: 10px;
        }
        a {
            color: #00BE31;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <div class="logoWrap"><img src="{{ asset('statics/hulshoff-logo.png') }}" alt=""></div>
            <div class="textWrap">
                {!! $data['content'] !!}
            </div>
        </div>
    </div>
</body>
</html>
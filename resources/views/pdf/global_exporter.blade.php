<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table{
        
        }
        th{
            padding: 5px;
        }
        td{
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="profile" style="color: rgb(162, 161, 161); margin-bottom: 10px;">
        <img src="{{ asset('uploads/images/logo_divi.png') }}" alt="" srcset="" width="140" height="100">
        <p style="margin:0; font-size: 0.75rem; ">Divi Tailor </p>
        <p style="margin:0; font-size: 0.75rem; ">+62 819 990 664 49</p>
        <p style="margin:0; font-size: 0.75rem; ">Jl. Gunung Agung Gg.Carik Denpasar, Bali</p>
</div>

    <table border="1" style="border-collapse: collapse; width: {{ $width }}">
        <thead>
            <tr>
                <th colspan="{{ count($headings) }}" style="font-weight: bold;  padding: 10px">{{ $title }}</th>
            </tr>
            <tr>

                @foreach($headings as $head)
                <th style="font-weight: bold;  padding: 10px">{{ $head }}</th>
                @endforeach

            </tr>
        </thead>
        <tbody>
            @foreach($data as $datum)
            <tr>
                <td>{{ $no++ }}</td>
                    @foreach($datum as $key => $value)
                        <td>{!! $value !!}</td>
                    @endforeach
            </tr>
            @endforeach
        </tbody>

</body>

</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <title></title>

        <!-- Fonts -->

        <!-- Scripts -->
    </head>
    <style>
        body{
            padding:20px;
        }
        .main{
            padding: 8px;
            margin-top: 5px;
            border: 1px solid black;
        }
        label{
            color: rgb(156 163 175);
            font-size: 14px;
            line-height: 20px;
        }
        .line{
            margin-top: 2px;
            margin-bottom: 2px;
            border-bottom: 1px solid rgb(156 163 175);
        }
        .line-dot{
            margin-top: 2px;
            margin-bottom: 2px;
            border-bottom: 1px rgb(156 163 175);
            border-style: dotted;
        }
        .text-lg{
            font-weight: bold;
            font-size: 1.125rem; /* 18px */
            line-height: 1.75rem; /* 28px */

        }
        .text-base{
            font-size: 1rem; /* 16px */
            line-height: 1.5rem; 24px
        }
        /* p{
            margin-bottom: 10px;
        } */
    </style>
    <body class="font-sans antialiased">
        <p class="text-lg">Divi Tailor Invoice</p>
        <!-- Page Content -->
        <div class='main'>
            <label>Nama Pemesan</label>
            <p class="text-lg">{{ $order->user->name }}</p>
            <div class="line"></div>
            <label>Nama Instansi</label>
            <p class="text-base">{{ $order->user->institute }}</p>
            <label>Alamat</label>
            <p class="text-base">{{ $order->user->address }}</p>
            <label>Kota</label>
            <p class="text-base">{{ $order->user->city }}</p>
            <label>No Telepon</label>
            <p class="text-base">{{ $order->user->phone_number }}</p>
            <label>Alamat Email</label>
            <p class="text-base">{{ $order->user->email }}</p>
        </div>
        <div class='main'>
            <label>Id Pesanan</label>
            <p class="text-lg">{{ $order->id }}</p>
            <div class="line"></div>
            <label>Tanggal</label>
            <p class="text-base">{{$carbon->createFromFormat('Y-m-d',$order->order_date )->translatedFormat('l')}}, {{ $order->order_date}}</p>
            <label>Status Pembayaran</label>
            <p class="text-base">{{ $order->payment->payment_status }}</p>
            <label>Jenis Baju</label>
            <p class="text-base">{{ $order->jenis_baju }}</p>
            <div class="line-dot"></div>
            <label>Total</label>
            <p class="text-base"><x-money amount="{{ $order->total_harga }}" currency="IDR" convert /></p>
        </div>
        <p>
            Catatan: 
        </p>
        <p>
            Pesanan yang sudah diterima tidak dapat dibatalkan.
        </p>

        <footer>

        </footer>
           
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>{{ $order->invoice_number }}</title>

    <!-- Fonts -->

    <!-- Scripts -->
</head>
<style>
    body {
        padding: 20px;
    }

    .main {
        padding: 8px;
        margin-top: 5px;
        border: 1px solid black;
    }

    label {
        color: rgb(156 163 175);
        font-size: 14px;
        line-height: 20px;
    }

    .line {
        margin-top: 2px;
        margin-bottom: 2px;
        border-bottom: 1px solid rgb(156 163 175);
    }

    .line-dot {
        margin-top: 2px;
        margin-bottom: 2px;
        border-bottom: 1px rgb(156 163 175);
        border-style: dotted;
    }

    .text-lg {
        font-weight: bold;
        font-size: 1.125rem;
        /* 18px */
        line-height: 1.75rem;
        /* 28px */

    }

    .text-base {
        font-size: 1rem;
        /* 16px */
        line-height: 1.5rem;
        24px
    }

    /* p{
            margin-bottom: 10px;
        } */

</style>
@php
$payment_status = [
0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran', 4 => 'Menunggu Konfirmasi
Pembayaran'
];
@endphp

<body class="font-sans antialiased">
    <div class="profile" style="color: rgb(162, 161, 161)">
        <img src="{{ asset('uploads/images/logo_divi.png') }}" alt="" srcset="" width="140" height="100">
        <p style="margin:0; font-size: 0.75rem; ">Divi Tailor </p>
        <p style="margin:0; font-size: 0.75rem; ">+62 819 990 664 49</p>
        <p style="margin:0; font-size: 0.75rem; ">Jl. Gunung Agung Gg.Carik Denpasar, Bali</p>
    </div>
    <p class="text-lg">Divi Tailor Invoice Pesanan</p>
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
        <label>Nomor Nota</label>
        <p class="text-lg">{{ $order->invoice_number }}</p>
        <div class="line"></div>
        <label>Tanggal</label>
        <p class="text-base">{{$carbon->createFromFormat('Y-m-d',$order->order_date )->translatedFormat('l')}},
            {{ $carbon->parse($order->order_date)->translatedFormat('d M Y') }}</p>
        <label>Tanggal Estimasi Selesai</label>
        <p class="text-base">{{$carbon->createFromFormat('Y-m-d',$order->tanggal_estimasi )->translatedFormat('l')}},
            {{ $carbon->parse($order->tanggal_estimasi)->translatedFormat('d M Y') }}</p>
        <label>Status Pembayaran</label>
        <p class="text-base">{{ $payment_status[$order->payment->payment_status] }}</p>
        <label>Jenis Pakaian</label>
        <p class="text-base">{{ config('const.jenis_pakaian')[$order->jenis_pakaian] }}</p>
        <label>Jenis Pesanan</label>
        <p class="text-base">{{ $order->jenis_pembuatan }}</p>
        <label>Jenis Kain</label>
        <p class="text-base">{{ config('const.jenis_kain')[$order->jenis_kain] }}</p>
        <label>Jenis Panjang</label>
        <p class="text-base">{{ config('const.jenis_panjang')[$order->jenis_panjang] }}</p>
        <label>Deskripsi Pakaian</label>
        <p class="text-base">{{ $order->deskripsi_pakaian }}</p>
        <div class="line-dot"></div>
        <label>Total</label>
        <p class="text-base">
            <x-money amount="{{ $order->total_harga }}" currency="IDR" convert />
        </p>
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

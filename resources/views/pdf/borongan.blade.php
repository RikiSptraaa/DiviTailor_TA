<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title></title>

    <!-- Fonts -->

    <!-- Scripts -->
</head>
<style>
    *{
        font-family: Arial, Helvetica, sans-serif;
    }
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
        line-height: 1.5rem 24px;
    }

    .profile {
        display: flex;
        justify-content: space-between;
    }

    /* p{
            margin-bottom: 10px;
        } */

</style>

<body class="font-sans antialiased">
    <!-- {{-- <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="120" height="120" viewBox="0 0 299.000000 156.000000" preserveAspectRatio="xMidYMid meet">

            <g fill="grey" transform="translate(0.000000,156.000000) scale(0.100000,-0.100000)"  stroke="none">
            <path d="M1149 1533 c-120 -74 -25 -234 117 -199 15 4 41 22 58 41 31 33 34 34 241 69 116 20 220 39 233 41 21 5 22 3 22 -61 l0 -67 68 6 c37 4 170 14 297 23 191 14 230 14 234 3 16 -50 99 -89 158 -75 44 12 93 68 93 109 0 48 -39 93 -92 108 -54 14 -123 -10 -147 -52 -10 -19 -27 -31 -46 -34 -36 -6 -500 -55 -521 -55 -11 0 -14 15 -14 65 0 36 -1 65 -2 65 -2 0 -109 -14 -238 -30 -129 -16 -241 -30 -247 -30 -7 0 -19 13 -28 30 -8 16 -29 36 -45 45 -38 20 -108 19 -141 -2z"/>
            <path d="M157 1224 c-16 -16 -5 -32 33 -47 22 -8 40 -21 40 -29 0 -21 -119 -610 -130 -644 -10 -29 -44 -54 -74 -54 -12 0 -26 -20 -26 -38 0 -4 78 -6 173 -6 213 2 260 15 348 104 193 193 225 610 53 696 -33 16 -65 19 -224 22 -102 2 -189 0 -193 -4z m335 -80 c44 -40 62 -101 61 -214 -1 -284 -128 -483 -290 -457 -19 4 -37 8 -39 10 -7 6 128 662 139 676 16 19 101 9 129 -15z"/>
            <path d="M1007 1211 c-4 -16 3 -22 37 -33 49 -16 49 2 -1 -253 -48 -248 -91 -436 -103 -451 -6 -6 -26 -15 -45 -18 -27 -5 -35 -12 -35 -29 0 -22 2 -22 147 -20 139 1 148 3 151 21 2 16 -5 22 -35 29 -27 6 -39 15 -41 29 -3 23 100 543 123 622 14 46 21 56 53 69 35 14 52 29 52 46 0 4 -67 7 -149 7 -139 0 -149 -1 -154 -19z"/>
            <path d="M1540 1210 c0 -14 12 -25 40 -36 l40 -16 0 -88 c0 -49 -7 -222 -16 -385 -8 -164 -13 -299 -10 -302 15 -15 40 18 104 139 91 173 339 608 360 631 9 10 27 23 41 30 14 7 27 20 29 30 3 15 -7 17 -102 17 -96 0 -106 -2 -106 -18 0 -11 11 -24 25 -30 14 -6 25 -17 25 -24 -1 -7 -57 -121 -125 -253 -103 -198 -125 -234 -125 -205 1 66 29 425 35 441 3 9 24 24 46 34 25 12 39 25 39 37 0 17 -11 18 -150 18 -144 0 -150 -1 -150 -20z"/>
            <path d="M2347 1210 c-4 -16 2 -22 34 -30 34 -9 39 -15 39 -40 0 -28 -98 -520 -123 -615 -12 -46 -17 -51 -55 -65 -31 -10 -42 -20 -42 -35 0 -19 5 -20 147 -18 137 1 148 3 151 20 2 15 -6 21 -35 29 -28 6 -39 15 -41 31 -4 26 102 558 124 626 13 40 23 52 53 64 34 15 51 29 51 46 0 4 -67 7 -149 7 -140 0 -149 -1 -154 -20z"/>
            <path d="M1810 303 c-32 -4 -60 -25 -60 -46 0 -5 25 -5 57 -1 52 6 56 5 49 -12 -13 -34 -29 -190 -22 -218 9 -36 36 -34 36 2 0 16 9 64 20 108 12 43 19 92 15 108 -5 28 -4 28 30 23 29 -5 35 -3 35 12 0 27 -59 35 -160 24z"/>
            <path d="M2488 273 c-35 -39 -64 -90 -79 -141 -21 -72 -99 -111 -107 -54 -6 42 -35 45 -88 7 -58 -42 -74 -44 -74 -10 0 33 -19 32 -78 -2 -65 -38 -44 -13 28 33 64 42 73 54 37 54 -33 0 -180 -88 -174 -104 3 -8 1 -18 -5 -24 -6 -6 -7 -15 -4 -21 12 -18 47 -12 94 15 40 24 46 25 54 10 13 -23 64 -20 105 5 l34 21 28 -26 c34 -32 46 -32 105 -1 35 19 46 21 46 10 0 -40 86 -43 139 -6 36 26 41 26 41 1 0 -34 42 -46 91 -27 22 9 46 14 53 11 7 -3 40 13 73 36 33 22 65 40 71 40 20 0 23 -19 7 -56 -8 -20 -13 -39 -10 -42 7 -7 77 15 100 32 26 19 11 34 -16 17 -27 -16 -34 -9 -19 19 14 27 8 44 -24 66 -29 18 -76 12 -76 -10 0 -7 -18 -25 -39 -39 l-40 -26 6 33 c6 36 6 36 -60 50 -23 6 -44 -3 -107 -44 -82 -52 -127 -63 -150 -35 -10 13 -6 22 28 59 48 52 82 115 82 151 0 35 -38 34 -72 -2z m22 -60 c0 -11 -52 -84 -57 -79 -6 6 38 86 48 86 5 0 9 -3 9 -7z m213 -119 c11 -11 -13 -45 -40 -55 -47 -18 -56 25 -10 47 27 14 42 16 50 8z"/>
            <path d="M2269 211 c-20 -16 -22 -22 -11 -33 10 -10 18 -10 40 2 33 19 42 50 14 50 -11 0 -30 -9 -43 -19z"/>
            </g>
            </svg> --}} -->
    <div class="profile" style="color: rgb(162, 161, 161)">
            <img src="{{ asset('uploads/images/logo_divi.png') }}" alt="" srcset="" width="140" height="100">
            <p style="margin:0; font-size: 0.75rem; ">Divi Tailor </p>
            <p style="margin:0; font-size: 0.75rem; ">+62 819 990 664 49</p>
            <p style="margin:0; font-size: 0.75rem; ">Jl. Gunung Agung Gg.Carik Denpasar, Bali</p>
    </div>
    <p class="text-lg">Divi Tailor Nota Borongan</p>
    <p style="font-size: 0.75rem;">{{  $carbon->parse($borongan->group_order_date)->dayName . ', ' . $carbon->parse($borongan->group_order_date)->format('d F Y'); }}</p>
    <!-- Page Content -->
    <div class='main'>
        <label>No Nota Borongan</label>
        <p class="text-lg">{{ $borongan->invoice_number }}</p>
        <div class="line"></div>
        <label>Nama Kelompok</label>
        <p class="text-base">{{ $borongan->group->group_name }}</p>
        <label>Instansi</label>
        <p class="text-base">{{ $borongan->group->institute }}</p>
        <label>Alamat Email</label>
        <p class="text-base">{{ $borongan->group->email }}</p>
        <label>Nomor Telepon</label>
        <p class="text-base">{{ $borongan->group->group_phone_number }}</p>
    </div>
    <div class="main">        
        <label>Tanggal Pesanan</label>
        <p class="text-base">{{$carbon->createFromFormat('Y-m-d',$borongan->group_order_date )->translatedFormat('l')}},
            {{ $carbon->parse($borongan->group_order_date)->translatedFormat('d M Y') }}</p>
        <label>Tanggal Estimasi Selesai</label>
        <p class="text-base">{{$carbon->createFromFormat('Y-m-d',$borongan->tanggal_estimasi )->translatedFormat('l')}},
            {{ $carbon->parse($borongan->tanggal_estimasi)->translatedFormat('d M Y') }}</p>
        <label>Jenis Pesanan</label>
        <p class="text-base">{{ $borongan->order_kind }}</p>
        {{-- <label>Status Pembayaran</label>
        <p class="text-base">{{ $payment_status[$borongan->payment->payment_status] ?? 'Belum Melakukan Pembayaran'}}</p> --}}
        <label>Jenis Pakaian</label>
        <p class="text-base">{{ config('const.jenis_pakaian')[$borongan->jenis_pakaian] }}</p>
        <label>Jenis Kain</label>
        <p class="text-base">{{ config('const.jenis_kain')[$borongan->jenis_kain] }}</p>
        <label>Jenis Panjang</label>
        <p class="text-base">{{ config('const.jenis_panjang')[$borongan->jenis_panjang] }}</p>
        <label>Deskripsi Pakaian</label>
        <p class="text-base">{{ $borongan->deskripsi_pakaian }}</p>
        <label>Harga Per Unit</label>
        <p class="text-base">        <x-money amount="{{ $borongan->price_per_item }}" currency="IDR" convert />
        </p>
        <label>Harga Total</label>
        <p class="text-base">        <x-money amount="{{ $borongan->price }}" currency="IDR" convert />
        </p>
        <label>Status Pembayaran</label>
        <ol>
        @php
            if (isset($borongan->payment)) {
                # code...
                $payment =  collect($borongan->payment)->sortBy(function ($item) {
                            return strtotime($item->paid_date); });
            }
        @endphp
        @foreach($payment as $key => $value)
            <li>
                <p>{{ config('const.status_pembayaran')[$value->paid_status] . ' ('.$carbon->parse($value->paid_date)->translatedFormat('d M Y').')' }}</p>
            </li>
        @endforeach
        </ol>        
    </div>
    <div class='main'>
        <p class="text-lg">Anggota Pelanggan Borongan</p>
        <label>Jumlah Anggota</label>
        <p class="text-base">{{ $borongan->users_total }} Orang</p>
        <label>Nama</label>
        @php
        $no = 1;
        @endphp
        @foreach($borongan->user
        as $pelanggan)

        <p class="text-base">{{$no++.'. '.$pelanggan->name }}</p>

        @endforeach

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

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Divi Tailor') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.2/datatables.min.css"/>


</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>

        </header>
        @endif

        <!-- Page Content -->
        <main>
            @auth
            {{-- Modal Buat Pesanan --}}
            <input type="checkbox" class="modal-toggle" id="modal-create-order" />
            <div class="modal">
                <div class="modal-box">
                    <label for="modal-create-order" class="btn btn-xs absolute left-2 top-2">✕</label>
                    <h3 class="font-bold text-lg my-4">Buat Pesanan</h3>
                    <form id="create-order-form" method="post">
                        @csrf
                        <div>
                            <x-input-label for="order_date" :value="__('Tanggal Pesanan')" />
                            <input type="date" name="tanggal_pesanan" id="order_date"
                                value="{{ old('tanggal_pesanan') }}"
                                class="input input-sm input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-date">
                            </ul>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="order_kind" :value="__('Jenis Pakaian')" />
                            <input type="text" name="jenis_pakaian" id="order_kind" value="{{ old('jenis_pakaian')}}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-order-kind">
                            </ul>
                        </div>
                        <div class="mt-4">
                            @if( !auth()->user()->baju()->exists() OR !auth()->user()->celana()->exists())
                            <p class="text-xs text-red-600">
                                Catatan: Halo {{ auth()->user()->name }}, Anda belum memiliki data ukuran di database
                                kami.
                                Lakukanlah pengukuran dengan cara datang ke toko kami atau jika memiliki data ukuruan
                                celana & baju silahkan hubungi kami
                                melalui <a href="https://wa.me/+6281999066449"
                                    class="btn-link text-green-400">whatsapp.</a>
                            </p>
                            @endif
                            <p class="text-xs text-gray-700 mb-2">Catatan: Pesanan pakaian yang baru dibuat harus
                                menunggu
                                diterima oleh
                                pihak Divi Tailor terlebih dahulu.</p>
                        </div>
                        <div class="modal-action">
                            <button for="modal-create-order" class="btn btn-sm" {{  !auth()->user()->baju()->exists() && !auth()->user()->celana()->exists() ? 'disabled' : '' }} 
                                id="btn-save">Buat Pesanan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Profile --}}
            <input type="checkbox" id="modal-profile" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box rounded-none">
                    <label for="modal-profile" class="btn btn-xs absolute left-2 top-2">✕</label>
                    <h3 class="font-bold text-lg text-center">Profile</h3>
                    <div class="flex justify-center my-6">
                        <div class="avatar overflow-visible">
                            <div class="w-24 rounded-full">
                                <img class="overflow-visible" src="{{ asset('img/blank-pfp.webp') }}" />
                            </div>
                        </div>
                    </div>
                    <form id="update-profile-form">
                        @csrf
                        @method('patch')
                        <div>
                            <x-input-label for="username" :value="__('Nama Pengguna')" />
                            <input type="text" name="nama_pengguna" id="username"
                                value="{{ old('nama_pengguna', auth()->user()->username) }}"
                                class="input input-sm input-bordered w-full focus:ring-black focus:border-none text-xs"
                                readonly />
                            <ul class='text-sm text-red-600 space-y-1' id="error-username">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <input type="text" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs"
                                readonly />
                            <ul class='text-sm text-red-600 space-y-1' id="error-email">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="full_name" :value="__('Nama Lengkap')" />
                            {{-- <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus /> --}}
                            <input type="text" name="nama_lengkap" id="full_name"
                                value="{{ old('nama_lengkap', auth()->user()->name) }}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-full-name">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
                            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required /> --}}
                            <input type="text" name="nomor_telepon" id="phone_number"
                                value="{{ old('nomor_telepon', auth()->user()->phone_number) }}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-phone-number">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="gender" :value="__('Jenis Kelamin')" />

                            <select name="jenis_kelamin"
                                class="select select-bordered focus:ring-black focus:border-none h-[35px] w-full min-h-[35px] text-xs">
                                <option disabled selected>Pilih Jenis Kelamin</option>
                                <option id="M" {{ auth()->user()->gender === 1 ? 'selected' : '' }} value="1">Laki-Laki
                                </option>
                                <option id="F" {{ auth()->user()->gender === 2 ? 'selected' : '' }} value="0">Perempuan
                                </option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat')" />

                            <textarea
                                class="textarea textarea-bordered w-full focus:ring-gray-600 focus:border-none text-xs"
                                name="alamat" id="address">{{ old('alamat', auth()->user()->address) }}</textarea>

                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-address">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="institute" :value="__('Asal Instansi')" />
                            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required /> --}}
                            <input type="text" name="instansi" id="institute"
                                value="{{ old('instansi',auth()->user()->institute) }}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-institute">
                            </ul>
                        </div>

                        {{-- Kota --}}
                        <div class="mt-4">
                            <x-input-label for="city" :value="__('Kota')" />
                            <input type="text" name="kota" id="city" value="{{ old('kota', auth()->user()->city) }}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-city">
                            </ul>
                        </div>
                        <div class="modal-action">
                            <button for="modal-profile" class="btn btn-sm" id="btn-save">Simpan Perubahan</button>
                            {{-- <label for="modal-profile" class="btn btn-sm">Simpan Perubahan</label> --}}
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Pembayaran Pesanan --}}
            <input type="checkbox" id="modal-pembayaran" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box">
                    <label for="modal-pembayaran" class="btn btn-xs absolute left-2 top-2">✕</label>
                    <h3 class="font-bold text-lg mt-4">Pembayaran Pesanan</h3>
                    <div class="modal-body">
                        <div class="card rounded-none">
                            <div class="card-body items-center text-center bg-base-100 shadow-xl">
                                <h2 class="card-title">Halo {{ auth()->user()->name }}</h2>
                                <p>Apakah benar detail pesanan sebagai berikut?</p>
                                <ul class="border border-gray-700 p-4" id="detail-pesanan">

                                </ul>
                                <p class="text-justify">Jika pesanan yang ingin dibayar sudah benar!</p>
                                <p class="text-xs">
                                    Silahkan melakukan pembayaran dengan cara
                                    transfer melalui bank dengan nomor rekening sebagai berikut:</p>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150"
                                        viewBox="4.445 2.992 462.37 145.055">
                                        <path fill="#005FAF" stroke="#005FAF"
                                            d="M255.169 72.04c29.217-13.252 33.538-56.497-8.021-56.497H199.02l-17.786 34.875h8.37l-21.274 83.351h55.8c40.709.348 59.017-47.588 31.039-61.729zm-31.039 31.388h-13.95l5.231-18.832h13.252c15.694 3.138 5.93 19.529-4.533 18.832zm8.37-40.107h-11.857l3.487-16.391h13.252c12.805 3.139 1.745 17.089-4.882 16.391z" />
                                        <path fill="#005FAF" stroke="#005FAF"
                                            d="M369.418 60.083l18.271-32.254-24.723-12.896-3.225 3.98c-13.337-7.06-83.176 2.688-87.549 71.581 2.614 57.793 67.373 47.397 73.725 43.73l17.811-36.819c-17.444 11.49-53.162 17.006-54.673-17.509 3.133-28.462 36.737-39.091 60.363-19.813zM460.592 15.543h-56.169l-17.854 33.831h9.172l-42.33 84.395h40.973l8.412-20.356h25.151l.627 20.356h37.717l-5.699-118.226zm-46.401 70.463l13.755-32.833.627 32.833h-14.382z" />
                                        <g fill="#005FAF">
                                            <path
                                                d="M109.826 128.125l2.182-.633-2.393-3.307zM74.004 127.562l-.422 2.956c1.086-.106 2.423.83 2.956-1.267-.04-1.634-1.284-1.724-2.534-1.689zM91.388 127.492c-.181-.805-.853-1.398-2.534-.844l.563 3.025c1.381-.306 2.091-1.123 1.971-2.181zM52.117 125.016l-.672 2.688c1.08.045 2.345.921 3.062-.672.048-.998.089-1.992-2.39-2.016zM89.91 131.926l.493 3.097c1.153.029 2.205-.664 1.9-2.111-.294-1.392-1.463-1.172-2.393-.986z" />
                                            <path
                                                d="M135.678 17.137C98.411-1.848 53.904-1.32 18.396 16.309c-18.31 32.233-18.893 77.332 0 117.276 39.119 19.796 82.385 18.492 117.557.828 16.53-33.913 19.26-74.721-.275-117.276zm-62.94 96.248l-2.209.025c-.547-6.104-2.006-11.234-5.136-15.16C51.04 80.251 21.7 93.802 29.239 63.213c3.92-15.638 48.633-24.861 43.499 50.172zM29.292 83.173c3.134 6.379 22.769 7.424 29.585 12.307 10.812 7.745 9.26 17.933 9.26 17.933h-2.55c-5.02-15.387-19.109-3.204-30.394-7.348-7.085-2.52-12.229-13.443-5.901-22.892zm35.774 50.161l1.056-8.375 1.9.141-.845 9.219c-.852 4.061-6.907 3.518-7.671-.352l.985-9.571 2.252.353-.985 8.022c.125 2.655 2.788 2.496 3.308.563zm-10.111 2.287l-2.166-.523c.254-1.79 2.377-5.432-1.819-5.432l-1.168 4.76-2.39-.448 2.539-11.427c6.206.676 6.749 2.403 6.872 4.257-.245 1.438-.628 2.442-1.867 2.688 1.45 1.316.017 4.074-.001 6.125zm-13.757-12.799c-.597.792-1.792 3.559-1.965 5.764-.184 2.337 2.061 1.845 2.62 1.572.451-.219.786-2.096.786-2.096l-1.572-.524.524-1.571 3.538 1.179-1.703 5.896-1.441-.394.131-.917c-1.764.804-4.993.201-5.372-1.834-.379-1.635.769-6.592 2.62-8.908 1.799-2.252 6.182-.051 6.289 1.31.106 1.356-.429 2.882-.429 2.882l-1.65-.311s.101-.49.245-1.262c.152-.818-1.981-1.637-2.621-.786zm32.173 9.596l-.422 5.068-1.759-.07.633-12.035c4.393.091 6.911.699 6.827 3.801-.262 4.238-3.694 3.285-5.279 3.236zm4.379-19.006h-2.943c-.261-10.422 1.743-29.485-3.733-43.514-4.149-10.635-14.649-15.419-16.976-24.01-3.684-13.598 2.586-28.311 23.652-29.444 18.263 1.875 23.874 15.974 20.759 29.101-5.356 13.125-10.713 11.239-17.382 24.313-5.447 14.015-3.373 32.376-3.377 43.554zm32.639 19.288l-2.041.704-.634-12.105 2.393-.633 5.912 9.993-1.619 1.056-1.971-2.814-2.252.844.212 2.955zm-23.811-19.288h-2.354c1.94-26.766 35.575-20.951 38.649-30.239 5.316 9.208 3.405 16.972-2.146 20.952-13.794 9.894-27.021-7.322-34.149 9.287zm12.481 13.518l1.056 5.56c.657 1.281 1.103 1.044 2.111.915 1.169-.846.925-1.67.704-2.745l2.041-.281c.532 4.028-1.383 4.439-2.815 4.715-3.071.22-3.673-.378-4.504-4.363-.484-3.411-1.32-7.013 1.548-7.671 4.708-.719 4.43 1.4 4.856 2.885l-2.041.353c-.326-1.276-.795-1.782-1.83-1.688-.943.433-1.33 1.134-1.126 2.32zm-4.293 5.77c.098 1.753-.497 2.917-1.619 3.308l-4.786.985-1.9-11.612c.982-.16 5.797-1.924 6.967 1.268.577 1.949-.021 2.648-.704 3.518 1.295.416 1.732 1.424 2.042 2.533zm-12.29-58.273c10.662-30.02 35.059-24.715 40.47-11.213 7.413 27.093-16.457 19.663-31.747 30.773-5.779 5.026-8.82 10.792-8.865 19.533h-2.62c-.157-17.606-.442-30.073 2.762-39.093z" />
                                        </g>
                                    </svg>

                                    <div class="border border-gray-700 p-4">
                                        <p class="text-gray-700">6115240017<p>
                                                <p class="text-gray-700 text-xs">
                                                    A.N : Ni Made Viviyanti
                                                    <p>
                                    </div>
                                    <div class="card-actions justify-end">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs">
                                Jika sudah melakukan transfer ke rekening diatas silahkan upload bukti transfer anda di
                                bawah ini.
                            </p>
                            <form id="payment-form" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <input type="hidden" name="order_id" id="order_id" value="">
                                <input type="file" class="file-input file-input-sm w-full max-w-xs"
                                    name="bukti_pembayaran" id="paid_file" />
                                <ul class='text-sm text-red-600 space-y-1' id="error-payment-file">
                                </ul>
                        </div>
                        <div class="modal-action">
                            <button type="submit" class="btn">Bayar</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
            {{ $slot }}
        </main>

        <footer class="footer footer-center p-10 bg-base-300 text-base-content rounded">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="60" height="60"
                    viewBox="0 0 299.000000 156.000000" preserveAspectRatio="xMidYMid meet">

                    <g fill="black" transform="translate(0.000000,156.000000) scale(0.100000,-0.100000)" stroke="none">
                        <path
                            d="M1149 1533 c-120 -74 -25 -234 117 -199 15 4 41 22 58 41 31 33 34 34 241 69 116 20 220 39 233 41 21 5 22 3 22 -61 l0 -67 68 6 c37 4 170 14 297 23 191 14 230 14 234 3 16 -50 99 -89 158 -75 44 12 93 68 93 109 0 48 -39 93 -92 108 -54 14 -123 -10 -147 -52 -10 -19 -27 -31 -46 -34 -36 -6 -500 -55 -521 -55 -11 0 -14 15 -14 65 0 36 -1 65 -2 65 -2 0 -109 -14 -238 -30 -129 -16 -241 -30 -247 -30 -7 0 -19 13 -28 30 -8 16 -29 36 -45 45 -38 20 -108 19 -141 -2z" />
                        <path
                            d="M157 1224 c-16 -16 -5 -32 33 -47 22 -8 40 -21 40 -29 0 -21 -119 -610 -130 -644 -10 -29 -44 -54 -74 -54 -12 0 -26 -20 -26 -38 0 -4 78 -6 173 -6 213 2 260 15 348 104 193 193 225 610 53 696 -33 16 -65 19 -224 22 -102 2 -189 0 -193 -4z m335 -80 c44 -40 62 -101 61 -214 -1 -284 -128 -483 -290 -457 -19 4 -37 8 -39 10 -7 6 128 662 139 676 16 19 101 9 129 -15z" />
                        <path
                            d="M1007 1211 c-4 -16 3 -22 37 -33 49 -16 49 2 -1 -253 -48 -248 -91 -436 -103 -451 -6 -6 -26 -15 -45 -18 -27 -5 -35 -12 -35 -29 0 -22 2 -22 147 -20 139 1 148 3 151 21 2 16 -5 22 -35 29 -27 6 -39 15 -41 29 -3 23 100 543 123 622 14 46 21 56 53 69 35 14 52 29 52 46 0 4 -67 7 -149 7 -139 0 -149 -1 -154 -19z" />
                        <path
                            d="M1540 1210 c0 -14 12 -25 40 -36 l40 -16 0 -88 c0 -49 -7 -222 -16 -385 -8 -164 -13 -299 -10 -302 15 -15 40 18 104 139 91 173 339 608 360 631 9 10 27 23 41 30 14 7 27 20 29 30 3 15 -7 17 -102 17 -96 0 -106 -2 -106 -18 0 -11 11 -24 25 -30 14 -6 25 -17 25 -24 -1 -7 -57 -121 -125 -253 -103 -198 -125 -234 -125 -205 1 66 29 425 35 441 3 9 24 24 46 34 25 12 39 25 39 37 0 17 -11 18 -150 18 -144 0 -150 -1 -150 -20z" />
                        <path
                            d="M2347 1210 c-4 -16 2 -22 34 -30 34 -9 39 -15 39 -40 0 -28 -98 -520 -123 -615 -12 -46 -17 -51 -55 -65 -31 -10 -42 -20 -42 -35 0 -19 5 -20 147 -18 137 1 148 3 151 20 2 15 -6 21 -35 29 -28 6 -39 15 -41 31 -4 26 102 558 124 626 13 40 23 52 53 64 34 15 51 29 51 46 0 4 -67 7 -149 7 -140 0 -149 -1 -154 -20z" />
                        <path
                            d="M1810 303 c-32 -4 -60 -25 -60 -46 0 -5 25 -5 57 -1 52 6 56 5 49 -12 -13 -34 -29 -190 -22 -218 9 -36 36 -34 36 2 0 16 9 64 20 108 12 43 19 92 15 108 -5 28 -4 28 30 23 29 -5 35 -3 35 12 0 27 -59 35 -160 24z" />
                        <path
                            d="M2488 273 c-35 -39 -64 -90 -79 -141 -21 -72 -99 -111 -107 -54 -6 42 -35 45 -88 7 -58 -42 -74 -44 -74 -10 0 33 -19 32 -78 -2 -65 -38 -44 -13 28 33 64 42 73 54 37 54 -33 0 -180 -88 -174 -104 3 -8 1 -18 -5 -24 -6 -6 -7 -15 -4 -21 12 -18 47 -12 94 15 40 24 46 25 54 10 13 -23 64 -20 105 5 l34 21 28 -26 c34 -32 46 -32 105 -1 35 19 46 21 46 10 0 -40 86 -43 139 -6 36 26 41 26 41 1 0 -34 42 -46 91 -27 22 9 46 14 53 11 7 -3 40 13 73 36 33 22 65 40 71 40 20 0 23 -19 7 -56 -8 -20 -13 -39 -10 -42 7 -7 77 15 100 32 26 19 11 34 -16 17 -27 -16 -34 -9 -19 19 14 27 8 44 -24 66 -29 18 -76 12 -76 -10 0 -7 -18 -25 -39 -39 l-40 -26 6 33 c6 36 6 36 -60 50 -23 6 -44 -3 -107 -44 -82 -52 -127 -63 -150 -35 -10 13 -6 22 28 59 48 52 82 115 82 151 0 35 -38 34 -72 -2z m22 -60 c0 -11 -52 -84 -57 -79 -6 6 38 86 48 86 5 0 9 -3 9 -7z m213 -119 c11 -11 -13 -45 -40 -55 -47 -18 -56 25 -10 47 27 14 42 16 50 8z" />
                        <path
                            d="M2269 211 c-20 -16 -22 -22 -11 -33 10 -10 18 -10 40 2 33 19 42 50 14 50 -11 0 -30 -9 -43 -19z" />
                    </g>
                </svg>
                <div class="grid grid-flow-col gap-4">
                    <a class="link link-hover" href="{{ route('orders.index') }}">Home</a>
                    <a class="link link-hover" href="{{ route('orders.index') }}" >Pesanan</a>
                    <a class="link link-hover" href="{{ route('borongan.index') }}">Borongan</a>
                    <a class="link link-hover" href="{{ route('payments.index') }}">Pembayaran</a>
                </div>
            </div>

            <div>
                <p class="font-bold text-base">Hubungi Kami</p>
                <div class="grid grid-flow-col gap-4">
                    <a href="https://wa.me/+6281999066449"><i class="fab fa-instagram fa-2x"></i></a>
                    <a><i class="fab fa-whatsapp fa-2x"></i></a>
                </div>
            </div>
            <div>

                <p>Copyright © 2023 - Divi Tailor</p>
            </div>
        </footer>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.2/datatables.min.js"></script>
    <script>
        AOS.init();

        function formatRupiah(money) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(money);
        }


        $(document).ready(function () {
            
            $('.form-decline').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var form_data = new FormData(this);

                Swal.fire({
                    title: 'Apakah Yakin Menolak Pesanan Grup?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'black',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tolak',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: form_data,
                            cache: false,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok',
                                    confirmButtonColor: 'black',
                                    title: 'Berhasil!',
                                    text: response.message,
                                }).then((result) => {
                                    location.reload()

                                });

                            },
                            error: function (error) {
                                Swal.fire({
                                    confirmButtonColor: 'black',
                                    title: 'Kesalahan!',
                                    text: error.responseJSON.message,
                                    icon: 'error',
                                })
                            },
                        });

                    }
                })


            });

            $('.form-acc').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var form_data = new FormData(this);

                Swal.fire({
                    title: 'Apakah Yakin Menerima Pesanan Grup?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'black',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Terima',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: form_data,
                            cache: false,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok',
                                    confirmButtonColor: 'black',
                                    title: 'Berhasil!',
                                    text: response.message,
                                }).then((result) => {
                                    location.reload()

                                });

                            },
                            error: function (error) {
                                Swal.fire({
                                    confirmButtonColor: 'black',
                                    title: 'Kesalahan!',
                                    text: error.responseJSON.message,
                                    icon: 'error',
                                })
                            },
                        });

                    }
                })
            });

            // Update Profile Script
            $('#update-profile-form').submit(function (e) {
                e.preventDefault();
                var form_data = new FormData(this);

                Swal.fire({
                    title: 'Apakah Yakin Mengubah Profile?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'black',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ubah',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('profile.update', isset(auth()->user()->username) ? auth()->user()->username : '') }}",
                            cache: false,
                            processData: false,
                            contentType: false,
                            data: form_data,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok',
                                    confirmButtonColor: 'black',
                                    title: 'Berhasil!',
                                    text: response.message,
                                }).then((result) => {
                                    $('#username').val(response.username);
                                    $('#full_name').val(response.name);
                                    $('#email').val(response.email);
                                    $('#city').val(response.city);
                                    $('#phone_number').val(response
                                        .phone_number);
                                    $('#address').html(response.address);
                                    $('#institute').val(response.institute);
                                    (response.gender == 1) ? $('#M').attr(
                                            'selected', 'selected'): $('#F')
                                        .attr('selected', 'selected');

                                    $('#error-username').css(
                                        'display', 'none');
                                    $('#error-city').css(
                                        'display', 'none');
                                    $('#error-full-name').css(
                                        'display', 'none');
                                    $('#error-phone-number').css(
                                        'display', 'none');
                                    $('#error-institute').css(
                                        'display', 'none');
                                    $('#error-address').css(
                                        'display', 'none');
                                    $('#error-email').css(
                                        'display', 'none');

                                    $('#profile-name').html('');
                                    $('#profile-name').html('Hi, ' +
                                        response.name);

                                });

                            },
                            error: function (error) {
                                Swal.fire({
                                    confirmButtonColor: 'black',
                                    title: 'Kesalahan!',
                                    text: 'Profile Gagal Diubah!',
                                    icon: 'error',
                                })
                                console.log(error);
                                $('#error-username').html('<li>' + error
                                    .responseJSON.nama_pengguna + '</li>').css(
                                    'display', '');
                                $('#error-city').html('<li>' + error
                                    .responseJSON.kota + '</li>').css(
                                    'display', '');
                                $('#error-full-name').html('<li>' + error
                                    .responseJSON.nama_lengkap + '</li>').css(
                                    'display', '');
                                $('#error-phone-number').html('<li>' + error
                                    .responseJSON.nomor_telepon + '</li>').css(
                                    'display', '');
                                $('#error-institute').html('<li>' + error
                                    .responseJSON.instansi + '</li>').css(
                                    'display', '');
                                $('#error-address').html('<li>' + error
                                    .responseJSON.alamat + '</li>').css(
                                    'display', '');
                                $('#error-email').html('<li>' + error
                                    .responseJSON.email + '</li>').css(
                                    'display', '');

                            },
                        });

                    }
                })
            });

            // Create Order Script
            $('#create-order-form').submit(function (e) {
                e.preventDefault();
                var form_data = new FormData(this);

                Swal.fire({
                    title: 'Apakah Yakin Membuat Pesanan Baru?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'black',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Buat',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('orders.store') }}",
                            cache: false,
                            processData: false,
                            contentType: false,
                            data: form_data,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok',
                                    confirmButtonColor: 'black',
                                    title: 'Berhasil!',
                                    text: response.message,
                                }).then((result) => {
                                    $('#error-date').css(
                                        'display', 'none');
                                    $('#error-order-kind').css(
                                        'display', 'none');

                                    location.reload();

                                });

                            },
                            error: function (error) {
                                Swal.fire({
                                    confirmButtonColor: 'black',
                                    title: 'Kesalahan!',
                                    text: error.responseJSON.message,
                                    icon: 'error',
                                })
                                console.log(error);
                                $('#error-date').html('<li>' + error
                                        .responseJSON.tanggal_pesanan ?? '' + '</li>')
                                    .css(
                                        'display', '');
                                $('#error-order-kind').html('<li>' + error
                                    .responseJSON.jenis_pakaian ?? '' + '</li>').css(
                                    'display', '');
                            },
                        });

                    }
                })
            });

            // Show Order Script
            $('.show-payment').click(function (e) {
                // e.preventDefault();
                var url_show = $(this).data('url');
                $.ajax({
                    type: "GET",
                    url: url_show,
                    cache: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        let harga = formatRupiah(response.total_harga);
                        $('#detail-pesanan').html(
                            "<li> Nomor Nota: " + response.invoice_number + "</li>" +
                            "<li> Jenis Pakaian: " + response.jenis_baju + "</li>" +
                            "<li> Tanggal: " + response.order_date + "</li>" +
                            "<li> Harga: " + harga + "</li>"
                        );
                        $('#order_id').val(response.id);


                    }
                });

            });

            // Payement Upload Script
            $('#payment-form').submit(function (e) {
                e.preventDefault();
                var form_data = new FormData(this);
                var order_id = $('#order_id').val();

                Swal.fire({
                    title: 'Unggah Bukti Bayar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'black',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Unggah',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "payment/" + order_id,
                            cache: false,
                            processData: false,
                            contentType: false,
                            data: form_data,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok',
                                    confirmButtonColor: 'black',
                                    title: 'Berhasil!',
                                    text: response.message,
                                }).then((result) => {
                                    location.reload();
                                });

                            },
                            error: function (error) {
                                Swal.fire({
                                    confirmButtonColor: 'black',
                                    title: 'Kesalahan!',
                                    text: error.responseJSON.message,
                                    icon: 'error',
                                })
                                console.log(error);
                                $('#error-payment-file').html('<li>' + error
                                        .responseJSON.bukti_pembayaran + '</li>')
                                    .css(
                                        'display', '');
                            },
                        });

                    }
                })

            });
        });

    </script>
    @yield('script')
</body>

</html>

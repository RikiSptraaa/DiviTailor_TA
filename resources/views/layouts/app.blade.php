<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Divi Tailor') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">



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
            <input type="checkbox" id="modal-profile" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box rounded-none">
                    <label for="modal-profile" class="btn btn-sm absolute right-2 top-2">✕</label>
                    <h3 class="font-bold text-lg text-center">Profile</h3>
                    <div class="flex justify-center my-6">
                        <div class="avatar">
                            <div class="w-24 rounded-full">
                                <img src="https://placeimg.com/192/192/people" />
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
                    <a class="link link-hover">Home</a>
                    <a class="link link-hover">Pesanan</a>
                    <a class="link link-hover">Borongan</a>
                    <a class="link link-hover">Pembayaran</a>
                </div>
            </div>

            <div>
                <p class="font-bold text-base">Hubungi Kami</p>
                <div class="grid grid-flow-col gap-4">
                    <a><i class="fab fa-instagram fa-2x"></i></a>
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
    <script>
        AOS.init();

        $(document).ready(function () {

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
                            url: "{{ route('profile.update', auth()->user()->username) }}",
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

        });

    </script>
</body>

</html>

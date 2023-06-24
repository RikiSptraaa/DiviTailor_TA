<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
    </h2>
    </x-slot> --}}
    {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="carousel w-full my-4">
            <div id="slide1" class="carousel-item relative w-full">
                <img src="https://placeimg.com/800/200/tech" class="w-full" />
                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                    <a href="#slide4" class="btn btn-circle">❮</a>
                    <a href="#slide2" class="btn btn-circle">❯</a>
                </div>
            </div>
            <div id="slide2" class="carousel-item relative w-full">
                <img src="https://placeimg.com/800/200/arch" class="w-full" />
                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                    <a href="#slide1" class="btn btn-circle">❮</a>
                    <a href="#slide3" class="btn btn-circle">❯</a>
                </div>
            </div>
            <div id="slide3" class="carousel-item relative w-full">
                <img src="https://placeimg.com/800/200/arch" class="w-full" />
                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                    <a href="#slide2" class="btn btn-circle">❮</a>
                    <a href="#slide4" class="btn btn-circle">❯</a>
                </div>
            </div>
            <div id="slide4" class="carousel-item relative w-full">
                <img src="https://placeimg.com/800/200/arch" class="w-full" />
                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                    <a href="#slide3" class="btn btn-circle">❮</a>
                    <a href="#slide1" class="btn btn-circle">❯</a>
                </div>
            </div>
        </div>
    </div> --}}


    <div data-aos="fade-up" data-aos-duration="1000" data-aos-once="true" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="hero min-h-screen" style="background-image: url({{ asset('img/homepage.webp') }});">
            <div class="hero-overlay bg-opacity-60"></div>
            <div class="hero-content text-center text-neutral-content">
                <div class="max-w-md">
                    <h1 class="mb-5 text-5xl font-bold" data-aos="fade-left" data-aos-duration="1000">Divi Tailor</h1>
                    <p class="mb-5 text-justify" data-aos="fade-left" data-aos-duration="1000">
                        Divi Tailor merupakan toko jasa jahit pakaian seragam di wilayah Denpasar yang sudah menerima
                        jasa jahit mulai dari individu sampai kelompok. Divi Tailor sudah berdiri sejak 1997.
                    </p>
                    <button class="btn btn-primary" data-aos="fade-left">Buat Pesanan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 mb-10">
            <div class="bg-white overflow-hidden shadow-sm rounded-none">
                <div class="p-4 text-gray-900 text-center">
                    <section class="overflow-hidden text-gray-700">
                        <div class="container px-5 py-2 mx-auto lg:pt-18 lg:px-28">
                            <h1 class="text-2xl font-bold">Jasa Menjahit Kami</h1>
                            <div class="flex justify-center pt-2 mb-5">
                                <hr class=" bg-gray-700 text-center w-[20%]">
                            </div>
                            <div class="flex flex-wrap -m-1 md:-m-2">
                                <div class="flex flex-wrap w-1/2">
                                    <div class="w-1/2 p-1 md:p-2">
                                        <img alt="gallery"
                                            class="block object-cover object-center w-full h-full rounded-lg"
                                            src="{{ asset('img/landing-img/benang.webp') }}">
                                    </div>
                                    <div class="w-1/2 p-1 md:p-2">
                                        <img alt="gallery"
                                            class="block object-cover object-center w-full h-full rounded-lg"
                                            src="{{ asset('img/landing-img/mesin.webp') }}">
                                    </div>
                                    <div class="w-full p-1 md:p-2">
                                        <img alt="gallery"
                                            class="block object-cover object-center w-full h-full rounded-lg"
                                            src="{{ asset('img/landing-img/jas1.webp') }}">
                                    </div>
                                </div>
                                <div class="flex flex-wrap w-1/2">
                                    <div class="w-full p-1 md:p-2">
                                        <img alt="gallery"
                                            class="block object-cover object-center w-full h-full rounded-lg"
                                            src="{{ asset('img/landing-img/jas2.webp') }}">
                                    </div>
                                    <div class="w-1/2 p-1 md:p-2">
                                        <img alt="gallery"
                                            class="block object-cover object-center w-full h-full rounded-lg"
                                            src="{{ asset('img/landing-img/motong.webp') }}">
                                    </div>
                                    <div class="w-1/2 p-1 md:p-2">
                                        <img alt="gallery"
                                            class="block object-cover object-center w-full h-full rounded-lg"
                                            src="{{ asset('img/landing-img/meteran.webp') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    {{-- <div class="border-b-[1px] border-solid border-b-gray-900 w-10"> --}}
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
        <h1 class="text-2xl font-bold text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">Proses Menjahit</h1>
        <div class="flex justify-center pt-2 mb-4">
            <hr class=" bg-gray-700 text-center w-[20%]">
        </div>
        <div class="md:grid md:grid-cols-4 sm:grid-cols-1 justify-center gap-40">
            <div class="text-center sm:mb-6" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
                <i class="fas fa-user-friends fa-5x mb-3"></i>
                <h1 class="text-xl font-bold">Konsultasi</h1>
            </div>
            <div class="text-center sm:mb-6" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
                <i class="fas fa-ruler-horizontal fa-5x mb-3"></i><br>
                <h1 class="text-xl font-bold">Pengukuran</h1>
            </div>
            <div class="text-center sm:mb-6" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
                <i class="fas fa-tasks fa-5x mb-3"></i>
                <h1 class="text-xl font-bold">Pengerjaan</h1>
            </div>
            <div class="text-center sm:mb-6" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
                <i class="fas fa-user-tie fa-5x mb-3"></i><br>
                <h1 class="judul text-xl font-bold">Pesanan Siap</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-20" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
        <div class="grid md:grid-cols-2 gap-8 sm:grid-cols-1">
            <div>
                <div class="mapouter w-full">
                    <div class="gmap_canvas"><iframe width="100%" height="300" id="gmap_canvas"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3944.4352443531957!2d115.18867277415968!3d-8.65008869139689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd23f55126886e7%3A0x51b31c7912b14e90!2sDIVI%20TAILOR%20%26%20DIVI%20BRIDAL%20SALON!5e0!3m2!1sen!2sid!4v1687593308427!5m2!1sen!2sid"
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a
                            href="https://123movies-to.org"></a><br>
                        <style>
                            .mapouter {
                                position: relative;
                                text-align: right;
                            }

                        </style><a href="https://www.embedgooglemap.net"></a>
                        <style>
                            .gmap_canvas {
                                overflow: hidden;
                                background: none !important;
                            }

                        </style>
                    </div>
                </div>

            </div>
            <div>
                <h1 class="text-5xl font-bold">Temukan Divi Tailor!</h1>
                <p class="py-6">Divi Tailor berlokasi pada Jl. Gunung Agung Gg.Carik, Padangsambian, Kec. Denpasar
                    Barat, Bali</p>
                <a href="https://www.google.com/maps/dir//DIVI+TAILOR+%26+DIVI+BRIDAL+SALON+85XR%2BXF9+Jl.+Gn.+Agung+Padangsambian,+Denpasar+Barat,+Denpasar+City,+Bali+80111/@-8.6500887,115.1912477,13z/data=!4m8!4m7!1m0!1m5!1m1!1s0x2dd23f55126886e7:0x51b31c7912b14e90!2m2!1d115.1912479!2d-8.6500877" 
                    class="btn btn-primary">Temukan Kami</a>
            </div>
        </div>

    </div>

</x-app-layout>

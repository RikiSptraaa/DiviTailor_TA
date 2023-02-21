@php
use Carbon\Carbon;
use App\Models\User;
$payment_status = [
0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran', 4 => 'Menunggu Konfirmasi
Pembayaran'
];
$task_status = [
0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap Digunakan'
];
$task_status_mapping =[];
$task_status_order = 1;

if (isset($group_order[1])) {
foreach($group_order[1] as $key => $value){
foreach($value['task'] as $task) {
$task_status_mapping[] = $task['task_status'];
}
}
}

if(in_array(0,$task_status_mapping)):
$task_status_order = 0;
endif
@endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 mb-10">
        <div class="card w-full bg-base-100 shadow-xl image-full mt-2" data-aos="fade-up" data-aos-duration="1000"
            data-aos-once="true">
            <div class="card-body">
                <h2 class="card-title">Buat Pesanan Borongan / Penasan Grup Anda Sekarang!</h2>
                <p>Apakah anda ingin membuat pesanan baru? Kami menawarkan jasa jahit dengan kualitas yang dijamin
                    sangat baik!</p>
                <div class="card-actions justify-end">
                    <label for="modal-create-group-order" class="btn">Buat Pesanan Grup</a>
                </div>
            </div>
        </div>
        {{-- Modal Buat Pesanan Grup --}}
        <input type="checkbox" class="modal-toggle" id="modal-create-group-order" />
        <div class="modal">
            <div class="modal-box">
                <label for="modal-create-group-order" class="btn btn-xs absolute left-2 top-2">✕</label>
                <h3 class="font-bold text-lg my-4">Buat Pesanan</h3>
                <form id="create-group-order-form" action="{{ route('borongan.store') }}" method="post">
                    @csrf
                    <div class="mt-4">
                        <x-input-label for="group" :value="__('Grup Pesanan')" />

                        <select name="grup"
                            class="select select-bordered focus:ring-black focus:border-none h-[35px] w-full min-h-[35px] text-xs">
                            <option disabled selected>Pilih Grup</option>
                            @foreach($group as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->group_name.'-'.$value->institute }}
                            </option>
                            @endforeach
                        </select>
                        <ul class='text-sm text-red-600 space-y-1' id="error-group-select">
                        </ul>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="order_date" :value="__('Tanggal Pesanan')" />
                        <input type="date" name="tanggal_pesanan" id="order_date" value="{{ old('tanggal_pesanan') }}"
                            class="input input-sm input-bordered w-full focus:ring-black focus:border-none text-xs" />
                        <ul class='text-sm text-red-600 space-y-1' id="error-group-order-date">
                        </ul>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="order_kind" :value="__('Jenis Pakaian')" />
                        <input type="text" name="jenis_pakaian" id="order_kind" value="{{ old('jenis_pakaian')}}"
                            class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                        <ul class='text-sm text-red-600 space-y-1' id="error-group-order-kind">
                        </ul>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="group-order-user" :value="__('Aggota Pesanan')" />

                        <select name="anggota_pelanggan[]" multiple="multiple" id="group-order-user-select"
                            class="text-sm" style="width: 100%;">
                            @foreach($user as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}
                            </option>
                            @endforeach
                        </select>
                        <ul class='text-sm text-red-600 space-y-1' id="error-group-order-user">
                        </ul>
                    </div>

                    <div class="mt-4">
                        @if( !auth()->user()->baju()->exists() OR !auth()->user()->celana()->exists())
                        <p class="text-xs text-red-600">
                            Catatan: Halo {{ auth()->user()->name }}, Anda belum memiliki data ukuran di database
                            kami.
                            Lakukanlah pengukuran dengan cara datang ke toko kami atau jika memiliki data ukuruan
                            celana & baju silahkan hubungi kami
                            melalui <a href="https://wa.me/+6281999066449" class="btn-link text-green-400">whatsapp.</a>
                        </p>
                        @endif
                        <p class="text-xs text-gray-700 mb-2">Catatan: Pesanan pakaian yang baru dibuat harus
                            menunggu
                            diterima oleh
                            pihak Divi Tailor terlebih dahulu.</p>
                        <p class="text-xs text-gray-700 mb-2">Pesanan Borongan hanya bisa dibayar oleh koordinator Grup
                        </p>
                    </div>
                    <div class="modal-action">
                        <button for="modal-create-order" class="btn btn-sm"
                            {{  !auth()->user()->baju()->exists() && !auth()->user()->celana()->exists() ? 'disabled' : '' }}
                            id="btn-save">Buat Pesanan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Pembayaran Pesanan Grup --}}
        <input type="checkbox" id="modal-pembayaran-borongan" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box">
                <label for="modal-pembayaran-borongan" class="btn btn-xs absolute left-2 top-2">✕</label>
                <h3 class="font-bold text-lg mt-4">Pembayaran Pesanan Borongan</h3>
                <div class="modal-body">
                    <div class="card rounded-none">
                        <div class="card-body items-center text-center bg-base-100 shadow-xl">
                            <h2 class="card-title">Halo {{ auth()->user()->name }}</h2>
                            <p>Apakah benar detail pesanan sebagai berikut?</p>
                            <ul class="border border-gray-700 p-4" id="detail-pesanan-borongan">

                            </ul>
                            <p class="text-justify">Jika pesanan yang ingin dibayar sudah benar!</p>
                            <p class="text-xs">
                                Pesanan borongan hanya dapat dibayar oleh koordinator grup, 
                                silahkan kumpulkan jumlah uang yang harus dibayar terlebih dahulu.
                            </p>
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
                        <form id="payment-group-order-form" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" name="group_order_id" id="group_order_id" value="">
                            <input type="file" class="file-input file-input-sm w-full max-w-xs" name="bukti_pembayaran_borongan"
                                id="group_paid_file" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-payment-group-file">
                            </ul>
                    </div>
                    <div class="modal-action">
                        <button type="submit" class="btn">Bayar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Pesanan Baru --}}
        <div class="mt-4 text-xl font-bold " data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
            Riwayat Pesanan Grup</div>
        <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2" data-aos="fade-up"
            data-aos-duration="1000" data-aos-once="true">
            <input type="checkbox" class="peer" id="collapse-new" />
            <label for="collapse-new" class="collapse-title text-lg font-semibold">
                Pesanan Grup Baru
            </label>
            <div class="collapse-content ">
                @if(!isset($group_order['']))
                Tidak Ada Pesanan Grup Yang Baru
                @else
                @foreach($group_order[''] as $key => $value)
                <div class="card w-full mt-2">
                    <div class="card-body border ">
                        <p class="font-semibold">{{ $value['group']['group_name'] }}-{{ $value['group']['institute'] }}
                        </p>

                        <p class="font-semibold text-lg">Koordinator Pesanan</p>
                        <p>{{  User::find($value['group']['coordinator'])->first()->name }}</p>
                        <p class="font-semibold text-lg">Tanggal</p>

                        <p>{{ Carbon::parse($value['group_order_date'])->dayName . ', ' . Carbon::parse($value['group_order_date'])->format('d F Y'); }}
                        </p>

                        <p class="font-semibold text-lg">Jenis</p>
                        <p>{{ $value['order_kind'] }}</p>

                        <div class="collapse collapse-arrow bg-base-100 mt-2">
                            <input type="checkbox" class="peer" id="collapse-user-new" />
                            <label for="collapse-user-new" class="collapse-title font-semibold">
                                Daftar Aggota
                            </label>
                            <div class="collapse-content ">

                                @foreach ($value['user'] as $user)
                                <ul>
                                    <li>{{ $user['name'] }}</li>
                                </ul>

                                @endforeach
                            </div>
                        </div>

                        <div class="badge  md:badge-md badge-sm badge-warning ">
                            Menunggu Diterima
                        </div>

                        <div class="card-actions justify-end">
                            <div class="card-actions justify-end">
                                <form id="form-delete" data-url="{{ url('group/orders/delete/'.$value['id']) }}">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-sm btn-warning btn-delete" type="submit">Batalkan Pesanan
                                        Grup</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        {{-- Pesanan Diterima --}}
        <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2" data-aos="fade-up"
            data-aos-duration="1000" data-aos-once="true">
            <input type="checkbox" class="peer" id="collapse-unpaid" />
            <label for="collapse-unpaid" class="collapse-title text-lg font-semibold">
                Pesanan Grup Diterima
            </label>
            <div class="collapse-content ">
                @if(!isset($group_order[1]))
                Tidak Ada Pesanan Grup
                @else
                @foreach($group_order[1] as $key => $value)
                <div class="card w-full mt-2">
                    <div class="card-body border ">
                        <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                            {{ $value['invoice_number'] }}
                        </h2>

                        <p class="font-semibold">{{ $value['group']['group_name'] }}-{{ $value['group']['institute'] }}
                        </p>

                        <p class="font-semibold text-lg">Koordinator Pesanan</p>
                        <p>{{  User::find($value['group']['coordinator'])->first()->name }}</p>
                        <p class="font-semibold text-lg">Tanggal</p>

                        <p>{{ Carbon::parse($value['group_order_date'])->dayName . ', ' . Carbon::parse($value['group_order_date'])->format('d F Y'); }}
                        </p>

                        <p class="font-semibold text-lg">Jenis</p>
                        <p>{{ $value['order_kind'] }}</p>

                        <div class="collapse collapse-arrow bg-base-100 mt-2">
                            <input type="checkbox" class="peer" id="collapse-user-acc" />
                            <label for="collapse-user-acc" class="collapse-title font-semibold">
                                Daftar Aggota
                            </label>
                            <div class="collapse-content ">

                                @foreach ($value['user'] as $user)
                                <ul>
                                    <a href="{{ route('profile.show', $user['id']) }}"><li>{{ $user['name'] }}</li></a>
                                </ul>

                                @endforeach
                            </div>
                        </div>

                        @if(!is_null($value['payment']))
                        <div class="badge md:badge-md badge-sm
                            {{ $value['payment']['paid_status'] == 0 ? 'badge-info' : ''  }}
                            {{ $value['payment']['paid_status'] == 1 ? 'badge-info' : ''  }}
                            {{ $value['payment']['paid_status'] == 2 ? 'badge-success' : ''  }}
                            {{ $value['payment']['paid_status'] == 3 ? 'badge-warning' : ''  }}">
                            <p>Status Pembayaran : {{ $payment_status[$value['payment']['paid_status']] }} </p>
                        </div>
                        @endif

                        @if(!is_null($value['task']) )
                        <div
                            class="badge  md:badge-md badge-sm {{ $task_status_order == 0 ? 'badge-warning' : 'badge-success' }}">
                            Status Pesanan : {{ $task_status_order == 0 ? $task_status[0] : $task_status[1] }}
                        </div>
                        @endif

                        <p>
                            Total Harga:
                            <span class="badge md:badge-md badge-sm">
                                <x-money amount="{{ $value['price'] }}" currency="IDR" convert />
                            </span>
                        </p>
                        <p>
                            Harga Satuan:
                            <span class="badge md:badge-md badge-sm">
                                <x-money amount="{{ $value['price_per_item'] }}" currency="IDR" convert />
                            </span>
                        </p>

                        <div class="card-actions justify-end">
                            <a class="btn btn-sm" target="_blank"
                                href="{{ route('borongan.print', $value['id']) }}">Cetak
                                Nota</a>
                            @if($value['group']['coordinator'] == auth()->user()->id)
                            <label for="modal-pembayaran-borongan" data-id="{{ $value['id'] }}"
                                data-url="{{ route('borongan.show', $value['id']) }}"
                                class="btn btn-sm show-payment {{ $value['payment']['paid_status'] == 2 ? 'hidden' : ''  }} {{ $value['payment']['paid_status'] == 4 ? 'hidden' : ''  }}">
                                Bayar
                            </label>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        {{-- Pesanan Ditolak --}}
        <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2" data-aos="fade-up"
            data-aos-duration="1000" data-aos-once="true">
            <input type="checkbox" class="peer" id="collapse-decline" />
            <label for="collapse-decline" class="collapse-title text-lg font-semibold">
                Pesanan Grup Ditolak
            </label>
            <div class="collapse-content ">
                @if(!isset($group_order[0]))
                Tidak Ada Pesanan Grup Yang Ditolak
                @else
                @foreach($group_order[0] as $key => $value)
                <div class="card w-full mt-2">
                    <div class="card-body border ">
                        <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                            {{ $value['invoice_number'] }}
                        </h2>
                        <p class="font-semibold">{{ $value['group']['group_name'] }}-{{ $value['group']['institute'] }}
                        </p>

                        <p class="font-semibold text-lg">Koordinator Pesanan</p>
                        <p>{{  User::find($value['group']['coordinator'])->first()->name }}</p>
                        <p class="font-semibold text-lg">Tanggal</p>

                        <p>{{ Carbon::parse($value['group_order_date'])->dayName . ', ' . Carbon::parse($value['group_order_date'])->format('d F Y'); }}
                        </p>

                        <p class="font-semibold text-lg">Jenis</p>
                        <p>{{ $value['order_kind'] }}</p>

                        <div class="collapse collapse-arrow bg-base-100 mt-2">
                            <input type="checkbox" class="peer" id="collapse-user-decline" />
                            <label for="collapse-user-decline" class="collapse-title font-semibold">
                                Daftar Aggota
                            </label>
                            <div class="collapse-content ">

                                @foreach ($value['user'] as $user)
                                <ul>
                                    <li>{{ $user['name'] }}</li>
                                </ul>

                                @endforeach
                            </div>
                        </div>

                        <div class="card-actions justify-end">
                            <div class="card-actions justify-end">
                                <form id="form-delete" data-url="{{ url('group/orders/delete/'.$value['id']) }}">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-sm btn-warning btn-delete" type="submit">Batalkan Pesanan
                                        Grup</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    @section('script')
    <script>
        $(document).ready(function () {
            $('#group-order-user-select').select2({
                placeholder: 'Pilih Anggota',
                theme: 'default'
            });

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
                        let harga = formatRupiah(response.price);
                        let harga_per_item = formatRupiah(response.price_per_item);
                        $('#detail-pesanan-borongan').html(
                            "<li> Nomor Nota: " + response.invoice_number + "</li>" +
                            "<li> Jenis Pakaian: " + response.order_kind + "</li>" +
                            "<li> Tanggal: " + response.group_order_date + "</li>" +
                            "<li> Total Pelanggan: " + response.users_total + "</li>" +
                            "<li> Harga Per Unit: " + harga_per_item + "</li>" +
                            "<li> Total Harga: " + harga + "</li>"
                        );
                        $('#group_order_id').val(response.id);


                    }
                });

            });

            $('#create-group-order-form').submit(function (e) {
                e.preventDefault();
                var form_data = new FormData(this);
                var url = $(this).attr('url');

                Swal.fire({
                    title: 'Apakah Yakin Membuat Pesanan Borongan Ini?',
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
                            url: url,
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
                                    icon: 'error',
                                    text: error.responseJSON.message
                                })
                                $('#error-group-order-date').html('<li>' + error
                                        .responseJSON.tanggal_pesanan ?? '' +
                                        '</li>')
                                    .css(
                                        'display', '');
                                $('#error-group-order-kind').html('<li>' + error
                                    .responseJSON.jenis_pakaian ?? '' + '</li>'
                                    ).css(
                                    'display', '');
                                $('#error-group-select').html('<li>' + error
                                    .responseJSON.grup ?? '' + '</li>').css(
                                    'display', '');
                                $('#error-group-order-user').html('<li>' + error
                                    .responseJSON.anggota_pelanggan ?? '' +
                                    '</li>').css(
                                    'display', '');
                            },
                        });

                    }
                })

            })

            $('#form-delete').submit(function (e) {
                var form_data = new FormData(this);
                var url = $(this).data('url');

                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Yakin Menghapus Pesanan Ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'black',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: url,
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
                                    // location.reload();

                                });

                            },
                            error: function (error) {
                                Swal.fire({
                                    confirmButtonColor: 'black',
                                    title: 'Kesalahan!',
                                    text: 'Pesanan Gagal Dihapus!',
                                    icon: 'error',
                                    footer: error.responseJSON.message
                                })
                            },
                        });

                    }
                })

            });

            $('#payment-group-order-form').submit(function (e) {
                e.preventDefault();
                var form_data = new FormData(this);
                var order_id = $('#group_order_id').val();

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
                            url: "orders/payment/" + order_id,
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
                                $('#error-payment-group-file').html('<li>' + error
                                        .responseJSON.bukti_pembayaran_borongan + '</li>')
                                    .css(
                                        'display', '');
                            },
                        });

                    }
                })

            });
        });

    </script>
    @endsection
</x-app-layout>

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
                    <label for="modal-create-order" class="btn">Buat Pesanan Grup</a>
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
                        <p class="font-semibold">{{ $value['group']['group_name'] }}-{{ $value['group']['institute'] }}</p>

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

                        <p class="font-semibold">{{ $value['group']['group_name'] }}-{{ $value['group']['institute'] }}</p>

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
                                        <li>{{ $user['name'] }}</li>
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
                            <label for="modal-pembayaran" data-id="{{ $value['id'] }}"
                                data-url="{{ '/order'.'/'.$value['id'] }}"
                                class="btn btn-sm show-payment {{ $value['payment']['paid_status'] == 2 ? 'hidden' : ''  }} {{ $value['payment']['paid_status'] == 4 ? 'hidden' : ''  }}">
                                Bayar / Bayar Sisa
                            </label>

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
                        <p class="font-semibold">{{ $value['group']['group_name'] }}-{{ $value['group']['institute'] }}</p>

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
            var url = $('#form-delete').data('url');
            $('#form-delete').submit(function (e) {
                var form_data = new FormData(this);
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
                                    location.reload();

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
        });

    </script>
    @endsection
</x-app-layout>

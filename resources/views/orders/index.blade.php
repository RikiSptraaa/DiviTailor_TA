@php
use Carbon\Carbon;
$waiting_order = isset($order['']) ? $order[''] : null;
$payment_status = [
0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran', 4 => 'Menunggu Konfirmasi Pembayaran'
];
$task_status = [
0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap Digunakan'
];

@endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 mb-10">
        {{-- Buat Pesanan --}}
        <div class="card w-full bg-base-100 shadow-xl image-full mt-2" data-aos="fade-up" data-aos-duration="1000"
            data-aos-once="true">
            <div class="card-body">
                <h2 class="card-title">Buat Pesanan Anda Sekarang!</h2>
                <p>Apakah anda ingin membuat pesanan baru? Kami menawarkan jasa jahit dengan kualitas yang dijamin
                    sangat baik!</p>
                <div class="card-actions justify-end">
                    <label for="modal-create-order" class="btn">Buat Pesanan</a>
                </div>
            </div>
        </div>
        <div class="mt-4 text-xl font-bold " data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
            Riwayat Pesanan</div>
        {{-- Pesanan Baru --}}
        <div data-aos="fade-up"
        data-aos-duration="1000" data-aos-once="true">
            <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2">
                <input type="checkbox" class="peer" id="collapse-lunas" />
                <label for="collapse-lunas" class="collapse-title text-lg font-semibold">
                    Pesanan Baru
                </label>
                <div class="collapse-content ">
                    @if(is_null($waiting_order))
                    Tidak Ada Pesanan Yang Dibuat
                    @else
                    @foreach($waiting_order as $key => $value)
                    <div class="card w-full">
                        <div class="card-body border ">
                            <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                                {{ $value['invoice_number'] }}
                            </h2>
                            <p>{{ Carbon::parse($value['order_date'])->dayName . ', ' . Carbon::parse($value['order_date'])->format('d F Y'); }}
                            </p>
                            <p>{{ $value['jenis_pembuatan'] }}</p>
                            <div class="badge badge-info">Menunggu Diterima</div>
                            <p></p>
                            <div class="card-actions justify-end">
                                <form id="form-delete" data-url="{{ url('order/delete/'.$value['id']) }}">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-sm btn-warning btn-delete" type="submit">Batalkan
                                        Pesanan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>

            {{-- Pesanan DIterima --}}
            <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2" >
                <input type="checkbox" class="peer" id="collapse-unpaid" />
                <label for="collapse-unpaid" class="collapse-title text-lg font-semibold">
                    Pesanan Diterima
                </label>
                <div class="collapse-content ">
                    @if(!isset($order[1]))
                        Tidak Ada Pesanan
                    @else
                        @foreach($order[1] as $key => $value)
                        @php
                            $lastPayment = array_key_last($value['payment']);
                        @endphp
                        <div class="card w-full mt-2">
                            <div class="card-body border ">
                                <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                                    {{ $value['invoice_number'] }}
                                </h2>
                                <p>{{ Carbon::parse($value['order_date'])->dayName . ', ' . Carbon::parse($value['order_date'])->format('d F Y'); }}
                                </p>
                                <p>{{ $value['jenis_pembuatan'] }}</p>

                                @if(isset( $value['payment'][$lastPayment]))
                                    
                                <div class="badge 
                                {{ $value['payment'][$lastPayment]['payment_status'] == 0 ? 'badge-info' : ''  }}
                                {{ $value['payment'][$lastPayment]['payment_status'] == 1 ? 'badge-info' : ''  }}
                                {{ $value['payment'][$lastPayment]['payment_status'] == 2 ? 'badge-success' : ''  }}
                                {{ $value['payment'][$lastPayment]['payment_status'] == 3 ? 'badge-warning' : ''  }}
                                ">
                                    <p>Status Pembayaran : {{ $payment_status[$value['payment'][$lastPayment]['payment_status']] }} </p>
                                </div>
                                @endif

                                @if(isset($value['task']) && !is_null($value['task']) )
                                <div class="badge {{ $value['task']['task_status'] == 0 ? 'badge-warning' : 'badge-success' }}">
                                    Status Pesanan : {{ $task_status[$value['task']['task_status']] }}
                                </div>
                                @endif

                                @php
                                if(isset($value['payment'][$lastPayment])){
                                    if ($value['payment'][$lastPayment]['payment_status'] == 0) {
                                    $sisa_harga = $value['total_harga'] - ($value['total_harga'] * 0.25);
                                    }
                                    elseif ($value['payment'][$lastPayment]['payment_status'] == 1) {
                                    $sisa_harga = $value['total_harga'] - ($value['total_harga'] * 0.5);
                                    }
                                }
                                @endphp
                                @if(isset($value['payment'][$lastPayment]))
                                    @if(isset($sisa_harga) && $value['payment'][0]['payment_status'] == 0 ||
                                    $value['payment'][$lastPayment]['payment_status'] == 1 )
                                    <div class="badge badge-info">Sisa Pembayaran:
                                        <x-money amount="{{ $sisa_harga}}" currency="IDR" convert />
                                    </div>
                                    @endif
                                @endif
                                <p>
                                    <x-money amount="{{ $value['total_harga'] }}" currency="IDR" convert />
                                </p>

                                <div class="card-actions justify-end">
                                    <a class="btn btn-sm" target="_blank" href="{{ route('orders.print', $value['id']) }}">Cetak
                                        Nota</a>
                                    <label for="modal-pembayaran" data-id="{{ $value['id'] }}" 
                                    data-url="{{ '/order'.'/'.$value['id'] }}"
                                        class="btn btn-sm show-payment {{ isset($value['payment'][$lastPayment]) && $value['payment'][$lastPayment]['payment_status'] == 2 ? 'hidden' : ''  }} {{ isset($value['payment'][$lastPayment]) && $value['payment'][$lastPayment]['payment_status'] == 4 ? 'hidden' : ''  }}">
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
            <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2">
                <input type="checkbox" class="peer" id="collapse-rejected" />
                <label for="collapse-rejected" class="collapse-title text-lg font-semibold">
                    Pesanan Ditolak
                </label>
                <div class="collapse-content ">
                    @if(!isset($order[0]))
                    Tidak Ada Pesanan Yang Ditolak
                    @else
                    @foreach($order[0] as $key => $value)
                    <div class="card w-full">
                        <div class="card-body border ">
                            <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                                {{ $value['invoice_number'] }}
                            </h2>
                            <p>{{ Carbon::parse($value['order_date'])->dayName . ', ' . Carbon::parse($value['order_date'])->format('d F Y'); }}
                            </p>
                            <p>{{ $value['jenis_pembuatan'] }}</p>
                            <p></p>
                            <div class="card-actions justify-end">
                                <form id="form-delete" class="form-del" data-url="{{ url('order/delete/'.$value['id']) }}" >
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-sm btn-warning btn-delete" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>

    


    @section('script')
    <script>
        $(document).ready(function () {
            var url = $('.form-del').data('url');
            $('.form-del').submit(function (e) {
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
                                    text: 'Pesanan Gagal Dibuat!',
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

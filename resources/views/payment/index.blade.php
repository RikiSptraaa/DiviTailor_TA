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
        <div class="text-lg font-semibold">Riwayat Pembayaran Pesanan</div>
        <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2" data-aos="fade-up"
            data-aos-duration="1000" data-aos-once="true">
            <input type="checkbox" class="peer" id="collapse-payment" />
            <label for="collapse-payment" class="collapse-title text-lg font-semibold">
                Pesanan Individu
            </label>
            <div class="collapse-content">
                <div class="card w-full">
                    @foreach($payment as $key => $value)
                    <div class="card-body border ">
                            
                        <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                            {{ $value['order']['invoice_number'] }}
                        </h2>
                    
                        <p>
                            {{ Carbon::parse($value['paid_date'])->dayName . ', ' . Carbon::parse($value['paid_date'])->format('d F Y'); }}
                        </p>
                        {{-- <p>
                            {{ $payment_status[$value['payment_status']] }}
                        </p> --}}
                        <div class="badge 
                        {{ $value['payment_status'] == 0 ? 'badge-info' : ''  }}
                        {{ $value['payment_status'] == 1 ? 'badge-info' : ''  }}
                        {{ $value['payment_status'] == 2 ? 'badge-success' : ''  }}
                        {{ $value['payment_status'] == 3 ? 'badge-warning' : ''  }}
                        ">
                            <p>Status Pembayaran : {{ $payment_status[$value['payment_status']] }} </p>
                        </div>
                        <div class="divider"></div> 
                        <h2 class="">
                            <x-money amount="{{ $value['order']['total_harga'] }}" currency="IDR" convert />
                        </h2>
                    
                        <div class="card-actions justify-end">
                            <form id="form-delete">
                                @method('delete')
                                @csrf
                                <a class="btn btn-sm" target="_blank" href="{{ route('orders.print', $value['order']['id']) }}">Cetak  </a>
                                <button class="btn btn-sm btn-warning btn-delete" type="submit">Batalkan
                                    Pesanan</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
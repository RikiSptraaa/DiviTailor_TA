<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 mb-10">
        <div class="card w-full bg-base-100 shadow-xl image-full mt-2" data-aos="fade-up" data-aos-duration="1000"
            data-aos-once="true">
            <div class="card-body">
                <h2 class="card-title">Buat Pesanan Borongan/ Penasan Grup Anda Sekarang!</h2>
                <p>Apakah anda ingin membuat pesanan baru? Kami menawarkan jasa jahit dengan kualitas yang dijamin
                    sangat baik!</p>
                <div class="card-actions justify-end">
                    <label for="modal-create-order" class="btn">Buat Pesanan Grup</a>
                </div>
            </div>
            <div class="mt-4 text-xl font-bold " data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
                Riwayat Pesanan Grup</div>

            <div class="collapse collapse-arrow border border-base-300 bg-base-100 mt-2" data-aos="fade-up"
                data-aos-duration="1000" data-aos-once="true">
                <input type="checkbox" class="peer" id="collapse-unpaid" />
                <label for="collapse-unpaid" class="collapse-title text-lg font-semibold">
                    Pesanan Grup Diterima
                </label>
                <div class="collapse-content ">
                    @if(!isset($group_order[1]))
                    Tidak Ada Pesanan
                    @else
                    @foreach($group_order[1] as $key => $value)
                    <div class="card w-full mt-2">
                        <div class="card-body border ">
                            <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                                {{ $value['invoice_number'] }}
                            </h2>
                            <p>{{ Carbon::parse($value['group_order_date'])->dayName . ', ' . Carbon::parse($value['group_order_date'])->format('d F Y'); }}
                            </p>
                            <p>{{ $value['order_kind'] }}</p>

                            <div class="badge 
                            {{ $value['payment']['payment_status'] == 0 ? 'badge-info' : ''  }}
                            {{ $value['payment']['payment_status'] == 1 ? 'badge-info' : ''  }}
                            {{ $value['payment']['payment_status'] == 2 ? 'badge-success' : ''  }}
                            {{ $value['payment']['payment_status'] == 3 ? 'badge-warning' : ''  }}
                            ">
                                <p>Status Pembayaran : {{ $payment_status[$value['payment']['payment_status']] }} </p>
                            </div>

                            @if(isset($value['task']) && !is_null($value['task']) )
                            <div
                                class="badge {{ $value['task']['task_status'] == 0 ? 'badge-warning' : 'badge-success' }}">
                                Status Pesanan : {{ $task_status[$value['task']['task_status']] }}
                            </div>
                            @endif

                            @php
                            if ($value['payment']['payment_status'] == 0) {
                            $sisa_harga = $value['total_harga'] - ($value['total_harga'] * 0.25);
                            }
                            elseif ($value['payment']['payment_status'] == 1) {
                            $sisa_harga = $value['total_harga'] - ($value['total_harga'] * 0.5);
                            }
                            @endphp
                            @if(isset($sisa_harga) && $value['payment']['payment_status'] == 0 ||
                            $value['payment']['payment_status'] == 1 )
                            <div class="badge badge-info">Sisa Pembayaran:
                                <x-money amount="{{ $sisa_harga}}" currency="IDR" convert />
                            </div>
                            @endif
                            <p>
                                <x-money amount="{{ $value['total_harga'] }}" currency="IDR" convert />
                            </p>

                            <div class="card-actions justify-end">
                                <a class="btn btn-sm" target="_blank"
                                    href="{{ route('orders.print', $value['id']) }}">Cetak
                                    Nota</a>
                                <label for="modal-pembayaran" data-id="{{ $value['id'] }}"
                                    data-url="{{ '/order'.'/'.$value['id'] }}"
                                    class="btn btn-sm show-payment {{ $value['payment']['payment_status'] == 2 ? 'hidden' : ''  }} {{ $value['payment']['payment_status'] == 4 ? 'hidden' : ''  }}">
                                    Bayar / Bayar Sisa
                                </label>

                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

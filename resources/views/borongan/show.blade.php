@php
use Carbon\Carbon;
    
@endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card card-side w-full bg-base-100 shadow-xl mt-4">
            <div class="card-body">
                <h2 class="card-title"><i class="fas fa-receipt fa-2x"></i>
                    {{ $borongan['invoice_number'] }}
                </h2>
                <p>{{ Carbon::parse($borongan['group_order_date'])->dayName . ', ' . Carbon::parse($borongan['group_order_date'])->format('d F Y'); }}
                </p>
                <p>{{ $borongan['order_kind'] }}</p>
                <div class="badge badge-success">{{ $borongan['is_acc'] == 1? 'Diterima' : 'Ditolak' }}</div>

                <div class="divider"></div> 
        
                <div class="flex justify-between">
                    <h2>Harga Per Unit</h2>
                    <h2 class="">
                        <x-money amount="{{ $borongan['price_per_item'] }}/Unit" currency="IDR" convert />
                    </h2>
                </div>
                <div class="flex justify-between">
                    <h2>Total</h2>
                    <h2 class="">
                        <x-money amount="{{ $borongan['price'] }}" currency="IDR" convert />
                    </h2>
                </div>
            </div>
        </div>
        <div class="card card-side w-full bg-base-100 shadow-xl mt-1">
            <div class="card-body">
                <h2 class="card-title"><i class="fas fa-users fa-2x"></i>
                    {{ $borongan['group']['group_code'].' ('. $borongan['group']['group_name'].'-'.$borongan['group']['institute'].')' }}
                </h2>
                <p>Anggota Pesanan</p>
                <ul>
                    @foreach($borongan['user'] as $key => $value)
                    <li>{{ $loop->iteration.'. '.$value['name'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
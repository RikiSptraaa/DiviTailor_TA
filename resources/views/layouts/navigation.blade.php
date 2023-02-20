@php
    use App\Models\GroupOrder;
    use Illuminate\Support\Facades\DB;
    if (auth()->check()) {
        # code...
        $notification = DB::table('group_order_users')->where('user_id', auth()->user()->id)->whereNull('acc_status');
        $notification_group = $notification->pluck('group_order_id');
        $notification = $notification->get();

        // $group_order = GroupOrder::with('group')->whereIn('id', $notification_group)->whereNull('is_acc')->orWhere('is_acc', '!=', 0)->get();
        $group_order = GroupOrder::with('payment', 'task')->whereHas('user', function($q){
            $q->where('user_id' , auth()->user()->id)->whereNull('acc_status');
        })->where('is_acc', true)->get();

    }
@endphp
<nav x-data="{ open: false }">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         {{-- navbar --}}
        <div class="navbar">
            <div class="flex-none lg:hidden">
                <div class="dropdown">
                    <label tabindex="0" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block w-6 h-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 w-52">
                      <li><a>Home</a></li>
                      <li><a>Galery</a></li>
                      <li><a>About</a></li>
                    </ul>
                </div>
               
            </div>
            <div class="flex-1">
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
            </div>
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1  lg:flex md:flex  sm:hidden hidden">
                    <li><a>Home</a></li>
                    <li><a>About</a></li>
                    <li><a>Galery</a></li>
                </ul>

                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <i class="fa fa-bell fa-lg"></i>
                            <span class="badge badge-sm indicator-item">{{ $group_order->count() ?? 0 }}</span>
                        </div>
                    </label>
                    <div tabindex="0" class="mt-3 card card-compact dropdown-content w-72 md:w-96 bg-base-100 shadow">

                        <div class="card-body bg-white text-black font-semibold">
                            Notifikasi Undangan Pesanan Grup
                            @auth
                                @if(isset($notification) && $notification->count() > 0)
                                    @foreach ($group_order as $item)
                                    <div class="border w-full p-2 flex justify-between align-middle align-items-center ">
                                        <div>
                                            <p class="text-md">{{ $item->invoice_number }}</p>
                                            <p class="text-xs">{{ $item->group->group_name.'-'.$item->group->institute }}</p>
                                        </div>
                                        <div class="flex align-items-center">
                                            <div>
                                                <form id="form-decline" class="form-decline" action="{{ route('borongan.delete-invitation', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-xs btn-ghost">Tolak</button>  
                                                </form>
                                            </div>
                                            <div>
                                                <form id="form-acc" class="form-acc" action="{{ route('borongan.acc-invitation', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-ghost">Terima</button>  
                                                </form>
                                            </div>
                                        </div>

                                    </div>     
                                    @endforeach

                                @endif   
                            @endauth

                        </div>
                    </div>
                </div>
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img src="{{ asset('img/blank-pfp.webp') }}" />
                        </div>
                    </label>
                    <ul tabindex="0"
                        class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-white text-black rounded-none w-52">
                        @guest
                        <li><a href="/login"
                                class="hover:bg-black hover:text-white active:bg-black active:text-white">Login</a>
                        </li>
                        <li><a href="/register"
                                class="hover:bg-black hover:text-white active:bg-black active:text-white">Daftar</a>
                        </li>

                        @endguest
                        @auth
                        <li id="profile-name" class="text-sm p-2">Hi, {{ auth()->user()->name }}</li>
                        <hr>

                        <li><label class="hover:bg-black hover:text-white" for="modal-profile">Profile</label>
                        </li>
                        <li><a class="hover:bg-black hover:text-white" href="{{ route('orders.index') }}">Pesanan</a></li>
                        <li><a class="hover:bg-black hover:text-white">Borongan</a></li>
                        <li><a class="hover:bg-black hover:text-white">Settings</a></li>
                        <hr>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <li><button type="submit" class="hover:bg-black hover:text-white">Logout</button>
                            </li>
                        </form>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
        {{-- end navbar --}}


    </div>
</nav>

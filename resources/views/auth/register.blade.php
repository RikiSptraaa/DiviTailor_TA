<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="username" :value="__('Nama Pengguna')" />
            {{-- <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus /> --}}
            <input type="text" name="username" id="username"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="full_name" :value="__('Nama Lengkap')" />
            {{-- <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus /> --}}
            <input type="text" name="full_name" id="full_name"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required /> --}}
            <input type="text" name="phone_number" id="phone_number"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Nomor Telepon -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required /> --}}
            <input type="email" name="email" id="email"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- gender --}}
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Jenis Kelamin')" />

            <select
                class="select select-bordered focus:ring-black focus:border-none h-[35px] w-full min-h-[35px] text-sm">
                <option disabled selected>Pilih Jenis Kelamin</option>
                <option value="1">Laki-Laki</option>
                <option value="0">Perempuan</option>
            </select>

            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        {{-- Alamat --}}
        <div class="mt-4">
            <x-input-label for="address" :value="__('Alamat')" />

            <textarea class="textarea textarea-bordered w-full" name="address" id="address"></textarea>

            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        {{-- Instansi --}}
        <div class="mt-4">
            <x-input-label for="institute" :value="__('Asal Instansi')" />
            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required /> --}}
            <input type="text" name="institute" id="institute"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('institute')" class="mt-2" />
        </div>

        {{-- Kota --}}
        <div class="mt-4">
            <x-input-label for="city" :value="__('Kota')" />
            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required /> --}}
            <input type="text" name="city" id="city"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            {{-- <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" /> --}}
            <input type="password" name="password" id="password"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <input type="password" name="password_confirmation" id="password_confirmation"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600"
                href="{{ route('login') }}">
                {{ __('Sudah Memiliki Akun?') }}
            </a>

            <button type="submit" class="btn btn-sm">Daftar</button>
        </div>
    </form>
</x-guest-layout>

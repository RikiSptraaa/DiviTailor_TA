<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Lupa password? Jangan khawatir kami akan mengirimkan link mengubah password ke email kalian!') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input type="text" placeholder="E-Mail" name="email"
            class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus /> --}}
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn ml-3">Kirim</button>
            {{-- <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button> --}}
        </div>
    </form>
</x-guest-layout>

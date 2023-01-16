<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus /> --}}
            <input type="text" placeholder="E-Mail"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            {{-- <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" /> --}}
            <input placeholder="Password" type="password" name="password" id="password"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />


            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
        </div> --}}

        <div class="form-control mt-4">
            <label class="label cursor-pointer" for="remember_me">
                <span class="label-text">Remember me</span>
                <input type="checkbox" id="remember_me" checked="checked" class="checkbox
               focus:border-none active:border-none focus:ring-black active:ring-black" name="remember" />
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div>

                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600"
                    href="{{ route('password.request') }}">
                    {{ ('Lupa password?') }}
                </a>
                @endif
            <br>
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600"
                    href="{{ '/register' }}">
                    {{ ('Daftar Akun') }}
                </a>
            </div>

            {{-- <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button> --}}
            <button class="btn ml-3">Log-In</button>
        </div>
    </form>
</x-guest-layout>

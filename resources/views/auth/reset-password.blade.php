<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <input type="password" name="password" id="password"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <input type="password" name="password_confirmation" id="password_confirmation"
                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="btn btn-sm ml-3">Reset Password Anda!</button>
        </div>
    </form>
</x-guest-layout>

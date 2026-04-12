<x-guest-layout>
    <x-slot name="logo">
        <a href="/">
            {{-- Michelle arahkan ke komponen logo yang sudah ada atau bisa Mas Robi ganti tag img langsung --}}
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Coat_of_arms_of_West_Nusa_Tenggara.svg/500px-Coat_of_arms_of_West_Nusa_Tenggara.svg.png" 
                 class="w-20 h-20" alt="Logo NTB">
        </a>
    </x-slot>

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Login</h2>
        <p class="text-md font-semibold text-blue-600">Manajemen Akun Inaproc</p>
        <hr class="mt-4 border-gray-200">
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            {{-- Forgot Password Michelle matikan sesuai permintaan di kode Buku Tamu --}}
            {{-- 
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif 
            --}}

            <x-primary-button class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-900 focus:border-blue-900 focus:ring-blue-300 transition-all duration-150">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
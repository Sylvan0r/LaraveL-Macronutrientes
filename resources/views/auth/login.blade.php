<x-guest-layout>
    <div class="flex justify-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800  shadow-lg p-6">
            <h2 class="text-yellow-400 text-3xl font-bold mb-6 text-center">Iniciar Sesión</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-white"/>
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border border-gray-600 text-gray-100 rounded p-2" 
                                  type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-white"/>
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border border-gray-600 text-gray-100 rounded p-2"
                                  type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-600 text-yellow-400 focus:ring-yellow-400" name="remember">
                    <label for="remember_me" class="ml-2 text-gray-200 text-sm">{{ __('Remember me') }}</label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-yellow-400 hover:text-yellow-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="bg-yellow-400 !text-gray-900 hover:bg-yellow-500 px-4 py-2 rounded shadow">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Botón para registro -->
            <div class="mt-6 text-center">
                <p class="text-gray-300 mb-2">Don't have an account created?</p>
                <a href="{{ route('register') }}" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded shadow">
                    Create Account
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>

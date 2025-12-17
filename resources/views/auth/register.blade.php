<x-guest-layout>
    <div class="flex justify-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800 shadow-lg p-6">
            <h2 class="text-yellow-400 text-3xl font-bold mb-6 text-center">Crear Cuenta</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" class="text-white"/>
                    <x-text-input id="name" class="block mt-1 w-full bg-gray-700 border border-gray-600 text-gray-100 rounded p-2" 
                                  type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-sm" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-white"/>
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border border-gray-600 text-gray-100 rounded p-2" 
                                  type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-white"/>
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border border-gray-600 text-gray-100 rounded p-2" 
                                  type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white"/>
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 border border-gray-600 text-gray-100 rounded p-2" 
                                  type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-sm" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="text-sm text-yellow-400 hover:text-yellow-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded shadow">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>


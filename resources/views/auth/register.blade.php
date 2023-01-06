<x-guest-layout>
    <div class="card p-4">
        <h2>{{ \App\Enums\RoleEnum::fromModel(request()->route('role'))->label() }} register</h2>
        <form method="POST" action="">
            @csrf

            <div class="mb-4">
                <x-input-label for="name" class="mb-1" :value="__('Name')" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="email" class="mb-1" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" class="mb-1" :value="__('Password')" />

                <x-text-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password_confirmation" class="mb-1" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation"
                                type="password"
                                name="password_confirmation" required />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('login', ['role' => request()->route('role')]) }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

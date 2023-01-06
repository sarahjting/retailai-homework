<x-app-layout>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-6 py-3 px-4">
            <h2>{{ __('Update user permissions') }}</h2>
            <div class="card p-4 mb-4">
                <h4>{{ __("User information") }}</h4>
                <div class="row">
                    <div class="fw-bold">
                        {{ __("Name") }}
                    </div>
                    <div>
                        {{ $user->name }}
                    </div>
                </div>
                <div class="row">
                    <div class="fw-bold">
                        {{ __("Email address") }}
                    </div>
                    <div>
                        {{ $user->email }}
                    </div>
                </div>
            </div>
            <div class="card p-4 mb-4">
                <h4>User permissions</h4>
                <form method="POST" action="{{ route('user_permissions.update', ['user' => $user]) }}">
                    @method('PUT')
                    @csrf
                    <div class="row mb-2">
                        <div>
                            <x-input-label for="roles" class="mb-1" :value="__('Roles')" />
                            @foreach (\App\Enums\RoleEnum::cases() as $roleEnum)
                                <x-checkbox-toggle-input
                                    name="roles[]"
                                    :value="$roleEnum->value"
                                    :id="sprintf('role_%s', $roleEnum->value)"
                                    :checked="$user->hasRole($roleEnum->value)"
                                    :disabled="!in_array($roleEnum, \App\Enums\RoleEnum::adminnableRoles())"
                                >
                                    {{ $roleEnum->label() }}
                                </x-checkbox-toggle-input>
                            @endforeach
                            <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div>
                            <x-input-label for="permissions" class="mb-1" :value="__('Permissions')" />
                            @foreach (\App\Enums\PermissionEnum::cases() as $permissionEnum)
                                <x-checkbox-toggle-input
                                    name="permissions[]"
                                    :value="$permissionEnum->value"
                                    :id="sprintf('permission_%s', $permissionEnum->value)"
                                    :checked="$user->hasPermissionTo($permissionEnum->value)"
                                    :disabled="!$permissionEnum->isAvailableToRoles($user->roles)">
                                    {{ $permissionEnum->label() }}
                                </x-checkbox-toggle-input>
                            @endforeach
                            <x-input-error :messages="collect($errors->get('permissions.*'))->join('')" class="mt-2" />
                        </div>
                    </div>
                    <x-primary-button class="w-100">{{ __('Update') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

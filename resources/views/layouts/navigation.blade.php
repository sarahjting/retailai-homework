<nav x-data="{ open: false }" class="navbar navbar-expand-md bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">{{ __("RetailAI Homework") }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </li>
                <li class="nav-item">
                    @can(\App\Enums\PermissionEnum::PRODUCTS_READ->value)
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                            {{ __('Products') }}
                        </x-nav-link>
                    @endcan
                </li>
                <li class="nav-item">
                    @role([\App\Enums\RoleEnum::ADMIN->value, \App\Enums\RoleEnum::SUPERADMIN->value])
                    <x-nav-link :href="route('products.admin.index')" :active="request()->routeIs('products.admin.index')">
                        {{ __('Products admin panel') }}
                    </x-nav-link>
                    @endrole
                </li>
                <li class="nav-item">
                    @role([\App\Enums\RoleEnum::SUPERADMIN->value])
                    <x-nav-link :href="route('user_permissions.index')" :active="request()->routeIs('user_permissions.index')">
                        {{ __('User admin panel') }}
                    </x-nav-link>
                    @endrole
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span href="#" class="nav-link">
                        {{ Auth::user()->name }}
                    </span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="nav-link" role="button" :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

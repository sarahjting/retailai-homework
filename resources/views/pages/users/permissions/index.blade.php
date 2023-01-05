<x-app-layout>
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-10 col-lg-8 py-3 px-4">
            <div class="card p-4">
                <h2>{{ __('User permissions dashboard') }}</h2>

                {!! $users->render() !!}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <td>{{ __("Email") }}</td>
                        <td>{{ __("Name") }}</td>
                        <td>{{ __("Roles") }}</td>
                    </tr>
                    </thead>
                    @foreach ($users as $user)
                        <tr>
                            <td style="vertical-align: middle">
                                <a href="{{ route('user_permissions.edit', ['user' => $user]) }}">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td style="vertical-align: middle">
                                {{ $user->name }}
                            </td>
                            <td style="vertical-align: middle">
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-info mr-2">{{ $role->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </table>
                {!! $users->render() !!}
            </div>
        </div>
    </div>
</x-app-layout>

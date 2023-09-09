<div>
    @auth
        User: {{ auth()->user()->email }}
        Continue as tenant:
        <div>
            @foreach ($tenants ?? [] as $tenant)
                <a href="{{ url(route('redirect-user-to-tenant', ['globalUserId' => auth()->user()->global_id, 'tenant' => $tenant])) }}">
                    {{ $tenant->getTenantKey() }}
                </a>
            @endforeach
        </div>
    @endauth

    <form action="{{ route('central-login') }}" method="POST">
        @csrf
        <input type="email" name="email">
        <input type="password" name="password">
        <button type="submit">Log in</button>
    </form>
</div>
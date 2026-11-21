@php
    // [label, route name, active pattern, icon, owner only]
    $items = [
        ['Dashboard', 'dashboard', 'dashboard', 'home', false],
        ['Inventory', 'inventory.index', 'inventory.*', 'cube', false],
        ['Residents', 'residents.index', 'residents.*', 'users', false],
        ['Rooms', 'rooms.index', 'rooms.*', 'building', false],
        ['Waiting List', 'waiting-list.index', 'waiting-list.*', 'clock', false],
        ['Rental Payments', 'payments.index', 'payments.*', 'banknotes', false],
        ['User Management', 'users.index', 'users.*', 'shield', true],
        ['Profile', 'profile.edit', 'profile.*', 'user-circle', false],
    ];
    $user = auth()->user();
    $initials = collect(explode(' ', trim($user->name)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
    $initials = mb_strtoupper($initials);
@endphp
<aside class="side" :class="{ 'is-open': open }" aria-label="Main navigation">
    <div class="side-head">
        <a href="{{ route('dashboard') }}" class="brand" aria-label="Renatha Living, dashboard">
            <strong>Renatha Living</strong>
            <span>Boarding house</span>
        </a>
        <button type="button" class="side-close" @click="open = false" aria-label="Close navigation"><x-icon name="x" class="" /></button>
    </div>

    <nav class="nav">
        @foreach ($items as [$label, $route, $pattern, $icon, $ownerOnly])
            @continue($ownerOnly && ! $user->isOwner())
            @if ($ownerOnly)
                <p class="nav-label">Administration</p>
            @endif
            <a href="{{ route($route) }}" @click="open = false" @if (request()->routeIs($pattern)) aria-current="page" @endif>
                <x-icon :name="$icon" class="" />
                {{ $label }}
            </a>
        @endforeach
    </nav>

    <div class="who">
        <div class="who-row">
            <span class="avatar">{{ $initials }}</span>
            <div>
                <p class="who-name">{{ $user->name }}</p>
                <p class="who-role">{{ $user->role }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn">Log out</button>
        </form>
    </div>
</aside>

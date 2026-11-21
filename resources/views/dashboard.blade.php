@php
    $rp = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $occupancy = $stats['total_rooms'] ? round($stats['occupied'] / $stats['total_rooms'] * 100) : 0;
    $paymentTone = ['paid' => 'green', 'partial' => 'amber', 'unpaid' => 'red'];
    $paymentLabel = ['paid' => 'Paid', 'partial' => 'Partially paid', 'unpaid' => 'Unpaid'];
    $floors = collect($rooms)->groupBy('floor')->sortKeysDesc();
@endphp
<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="intro">
        <div>
            <h1>{{ $greeting }}, {{ strtok(auth()->user()->name, ' ') }}</h1>
            <p class="lead">Here is how Renatha Living is doing today.</p>
        </div>
        @if ($isDemo)
            <p class="note">Demo data. Live records connect in the next step.</p>
        @endif
    </div>

    <dl class="figures">
        <x-stat-card label="Total rooms" :value="$stats['total_rooms']" hint="Renatha Living" />
        <x-stat-card label="Occupied" :value="$stats['occupied']" :hint="$occupancy.'% occupancy'" />
        <x-stat-card label="Available" :value="$stats['available']" hint="Ready for move-in" />
        <x-stat-card label="Active residents" :value="$stats['active_residents']" hint="Currently living in" />
        <x-stat-card label="Prospective" :value="$stats['prospective']" hint="On the waiting list" />
        <x-stat-card label="Low stock" :value="$stats['low_stock']" :tone="$stats['low_stock'] > 0 ? 'alert' : null" :hint="'of '.$stats['inventory_total'].' items tracked'" />
    </dl>

    <x-panel title="Room occupancy" :subtitle="'All '.$stats['total_rooms'].' rooms at a glance'" :href="route('rooms.index')" action="Manage rooms">
        <div class="occupancy">
            <div class="occupancy-row">
                <span>{{ $stats['occupied'] }} of {{ $stats['total_rooms'] }} occupied</span>
                <span>{{ $occupancy }}%</span>
            </div>
            <div class="meter" role="progressbar" aria-valuenow="{{ $occupancy }}" aria-valuemin="0" aria-valuemax="100" aria-label="Occupancy rate">
                <span style="width: {{ $occupancy }}%"></span>
            </div>
            <div class="legend">
                <span><i class="swatch"></i> Occupied</span>
                <span><i class="swatch swatch--free"></i> Available</span>
            </div>
        </div>

        @foreach ($floors as $floor => $floorRooms)
            <div class="floor">
                <h3>Floor {{ $floor }}</h3>
                <div class="rooms">
                    @foreach ($floorRooms as $room)
                        @php $occ = $room['status'] === 'occupied'; @endphp
                        <article class="room room--{{ $room['status'] }}">
                            <div class="room-top">
                                <p class="room-no">{{ $room['number'] }}</p>
                                <p class="room-state">{{ $occ ? 'Occupied' : 'Available' }}</p>
                            </div>
                            <div>
                                @if ($occ)
                                    <p class="room-who">{{ $room['resident'] }}</p>
                                    <p class="room-meta">Since {{ $room['since']->format('j M Y') }}</p>
                                @elseif ($room['offered_to'])
                                    <p class="room-offer">Offered to {{ $room['offered_to'] }}</p>
                                @else
                                    <p class="room-meta">Ready for move-in</p>
                                @endif
                                <p class="room-meta">{{ $rp($room['rate']) }}/mo</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endforeach
    </x-panel>

    <div class="split">
        <x-panel title="Low stock" subtitle="At or below the minimum threshold" :href="route('inventory.index')" action="Inventory">
            @if (count($lowStock))
                <div class="scroll">
                    <table class="data">
                        <caption class="visually-hidden">Supplies at or below their minimum</caption>
                        <thead><tr><th scope="col">Item</th><th scope="col" class="num">In stock</th><th scope="col">Status</th></tr></thead>
                        <tbody>
                            @foreach ($lowStock as $item)
                                @php
                                    $out = $item['stock'] === 0;
                                    $pct = min(100, (int) round($item['stock'] / max($item['min'], 1) * 100));
                                @endphp
                                <tr>
                                    <td>
                                        <span class="cell-name">{{ $item['name'] }}</span>
                                        <span class="cell-sub">{{ $item['category'] }}</span>
                                        <div class="meter {{ $out ? 'meter--error' : 'meter--warn' }}"><span style="width: {{ max($pct, 3) }}%"></span></div>
                                    </td>
                                    <td class="num">
                                        <span class="cell-name">{{ $item['stock'] }}</span>
                                        <span class="cell-sub">min {{ $item['min'] }} {{ $item['unit'] }}</span>
                                    </td>
                                    <td><x-badge :tone="$out ? 'red' : 'amber'">{{ $out ? 'Out of stock' : 'Low' }}</x-badge></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="empty-line">All supplies are stocked. Nothing has reached its minimum level.</p>
            @endif
        </x-panel>

        <x-panel title="Recent rental payments" subtitle="Latest manually recorded payments" :href="route('payments.index')" action="All payments">
            @if (count($payments))
                <div class="scroll">
                    <table class="data">
                        <caption class="visually-hidden">Recent rental payments</caption>
                        <thead><tr><th scope="col">Resident</th><th scope="col" class="num">Paid</th><th scope="col">Date</th><th scope="col">Status</th></tr></thead>
                        <tbody>
                            @foreach ($payments as $p)
                                <tr>
                                    <td>
                                        <span class="cell-name">{{ $p['resident'] }}</span>
                                        <span class="cell-sub">Room {{ $p['room'] }}, {{ $p['period'] }}</span>
                                    </td>
                                    <td class="num">
                                        <span class="cell-name">{{ $rp($p['paid']) }}</span>
                                        @if ($p['paid'] < $p['due'])
                                            <span class="cell-sub">of {{ $rp($p['due']) }}</span>
                                        @endif
                                    </td>
                                    <td><span class="cell-text">{{ $p['date']?->format('j M Y') ?? 'Not yet paid' }}</span></td>
                                    <td><x-badge :tone="$paymentTone[$p['status']]">{{ $paymentLabel[$p['status']] }}</x-badge></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="empty-line">No payments recorded yet.</p>
            @endif
        </x-panel>
    </div>
</x-app-layout>

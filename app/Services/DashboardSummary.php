<?php

namespace App\Services;

use Illuminate\Support\Carbon;

/**
 * Single data source for the dashboard view.
 *
 * Step 1 returns demo data. Later steps replace the private rooms()/inventory()/
 * waitingCount()/payments() methods with Eloquent queries that return the SAME shapes,
 * and set 'isDemo' to false. The dashboard view and its components do not change.
 */
class DashboardSummary
{
    public function get(): array
    {
        $rooms = $this->rooms();
        $inventory = $this->inventory();

        // Same rule the real inventory module will use: stock <= minimum threshold.
        $lowStock = array_values(array_filter($inventory, fn ($i) => $i['stock'] <= $i['min']));
        usort($lowStock, fn ($a, $b) => ($a['stock'] / max($a['min'], 1)) <=> ($b['stock'] / max($b['min'], 1)));

        $occupied = count(array_filter($rooms, fn ($r) => $r['status'] === 'occupied'));

        return [
            'isDemo' => true,
            'stats' => [
                'total_rooms' => count($rooms),
                'occupied' => $occupied,
                'available' => count($rooms) - $occupied,
                'active_residents' => $occupied, // one resident per room
                'prospective' => $this->waitingCount(),
                'low_stock' => count($lowStock),
                'inventory_total' => count($inventory),
            ],
            'rooms' => $rooms,
            'lowStock' => $lowStock,
            'payments' => $this->payments(),
        ];
    }

    /** @return array<int, array{number:string,floor:int,status:string,resident:?string,since:?Carbon,rate:int,offered_to:?string}> */
    private function rooms(): array
    {
        // number => [resident, move-in date, offered-to]
        $demo = [
            '101' => ['Sari Wulandari', '2025-03-01', null],
            '102' => [null, null, null],
            '103' => ['Rudi Hartono', '2025-08-15', null],
            '104' => ['Maya Anggraini', '2026-01-10', null],
            '105' => ['Fajar Nugroho', '2026-02-01', null],
            '106' => [null, null, 'Dian Pratama'],
            '201' => ['Lestari Putri', '2024-11-05', null],
            '202' => ['Bagas Prakoso', '2025-06-20', null],
            '203' => ['Nadia Safitri', '2026-05-12', null],
            '204' => [null, null, null],
            '205' => ['Hendra Gunawan', '2025-10-01', null],
            '206' => ['Citra Dewi', '2026-09-01', null],
        ];

        $rooms = [];
        foreach ($demo as $number => [$resident, $since, $offeredTo]) {
            $numberStr = (string) $number;
            $floor = (int) $numberStr[0];
            $rooms[] = [
                'number' => $numberStr,
                'floor' => $floor,
                'status' => $resident ? 'occupied' : 'available',
                'resident' => $resident,
                'since' => $since ? Carbon::parse($since) : null,
                'rate' => $floor === 1 ? 1200000 : 1500000,
                'offered_to' => $offeredTo,
            ];
        }

        return $rooms;
    }

    /** @return array<int, array{name:string,category:string,stock:int,min:int,unit:string}> */
    private function inventory(): array
    {
        return [
            ['name' => 'Drinking water gallon', 'category' => 'Kitchen', 'stock' => 0, 'min' => 2, 'unit' => 'gallons'],
            ['name' => 'Floor cleaner', 'category' => 'Cleaning', 'stock' => 2, 'min' => 3, 'unit' => 'liters'],
            ['name' => 'Toilet paper', 'category' => 'Toiletries', 'stock' => 10, 'min' => 12, 'unit' => 'rolls'],
            ['name' => 'LED bulb 12W', 'category' => 'Electrical', 'stock' => 4, 'min' => 4, 'unit' => 'pcs'],
            ['name' => 'Trash bags (large)', 'category' => 'Cleaning', 'stock' => 40, 'min' => 20, 'unit' => 'pcs'],
            ['name' => 'Hand soap refill', 'category' => 'Toiletries', 'stock' => 9, 'min' => 5, 'unit' => 'bottles'],
            ['name' => 'Mop head', 'category' => 'Cleaning', 'stock' => 5, 'min' => 2, 'unit' => 'pcs'],
            ['name' => 'Dish soap', 'category' => 'Kitchen', 'stock' => 6, 'min' => 4, 'unit' => 'bottles'],
            ['name' => 'Laundry detergent', 'category' => 'Cleaning', 'stock' => 7, 'min' => 5, 'unit' => 'kg'],
        ];
    }

    private function waitingCount(): int
    {
        return 4;
    }

    /** @return array<int, array{resident:string,room:string,period:string,paid:int,due:int,date:?Carbon,status:string}> */
    private function payments(): array
    {
        $period = now()->format('M Y');

        return [
            ['resident' => 'Sari Wulandari', 'room' => '101', 'period' => $period, 'paid' => 1200000, 'due' => 1200000, 'date' => now()->subDay(), 'status' => 'paid'],
            ['resident' => 'Rudi Hartono', 'room' => '103', 'period' => $period, 'paid' => 1200000, 'due' => 1200000, 'date' => now()->subDays(2), 'status' => 'paid'],
            ['resident' => 'Lestari Putri', 'room' => '201', 'period' => $period, 'paid' => 750000, 'due' => 1500000, 'date' => now()->subDays(3), 'status' => 'partial'],
            ['resident' => 'Bagas Prakoso', 'room' => '202', 'period' => $period, 'paid' => 1500000, 'due' => 1500000, 'date' => now()->subDays(4), 'status' => 'paid'],
            ['resident' => 'Maya Anggraini', 'room' => '104', 'period' => $period, 'paid' => 0, 'due' => 1200000, 'date' => null, 'status' => 'unpaid'],
            ['resident' => 'Citra Dewi', 'room' => '206', 'period' => $period, 'paid' => 1500000, 'due' => 1500000, 'date' => now()->subDays(6), 'status' => 'paid'],
        ];
    }
}

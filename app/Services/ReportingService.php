<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Mechanic;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;

class ReportingService
{
    /**
     * Gauti mechanikų darbo apkrovos statistiką
     */
    public function getMechanicWorkload(): Collection
    {
        return DB::table('orders')
            ->join('mechanics', 'orders.mechanic_id', '=', 'mechanics.id')
            ->select(
                'mechanics.id',
                'mechanics.name',
                'mechanics.specialization',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_price) as total_earnings'),
                DB::raw('AVG(orders.total_price) as average_order_price'),
                DB::raw('COUNT(CASE WHEN orders.status = "completed" THEN 1 END) as completed_orders'),
                DB::raw('COUNT(CASE WHEN orders.status = "in_progress" THEN 1 END) as in_progress_orders')
            )
            ->groupBy('mechanics.id', 'mechanics.name', 'mechanics.specialization')
            ->orderBy('total_orders', 'desc')
            ->get();
    }

    /**
     * Gauti paslaugų populiarumo statistiką
     */
    public function getServicePopularity(): Collection
    {
        return Service::select('services.id', 'services.name')
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('SUM(orders.total_price) as total_revenue')
            ->selectRaw('AVG(orders.total_price) as average_order_price')
            ->leftJoin('orders', 'services.id', '=', 'orders.service_id')
            ->groupBy('services.id', 'services.name')
            ->orderBy('total_orders', 'desc')
            ->get();
    }

    /**
     * Gauti detalią užsakymų statistiką pagal laikotarpį
     */
    public function getOrderStatistics(string $period = 'month'): Collection
    {
        $dateFormat = '';
        
        switch($period) {
            case 'day':
                $dateFormat = '%Y-%m-%d';
                break;
            case 'week':
                $dateFormat = '%x-%v'; // ISO week
                break;
            case 'month':
                $dateFormat = '%Y-%m';
                break;
            case 'year':
                $dateFormat = '%Y';
                break;
            default:
                $dateFormat = '%Y-%m';
        }
        
        return DB::table('orders')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
                DB::raw('COUNT(id) as total_orders'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('AVG(total_price) as average_order_price'),
                DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_orders'),
                DB::raw('COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled_orders')
            )
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();
    }
} 
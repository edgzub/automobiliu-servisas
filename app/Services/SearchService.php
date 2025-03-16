<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Car;
use App\Models\Service;
use App\Models\Mechanic;
use Illuminate\Database\Eloquent\Collection;

class SearchService
{
    /**
     * Paieška pagal raktažodį visuose susijusiuose objektuose
     * Elementų paieška kolekcijoje
     */
    public function searchByKeyword(string $keyword): Collection
    {
        return Order::where('description', 'LIKE', "%{$keyword}%")
            ->orWhere('status', 'LIKE', "%{$keyword}%")
            ->orWhereHas('car', function($query) use ($keyword) {
                $query->where('valstybinis_numeris', 'LIKE', "%{$keyword}%")
                    ->orWhere('marke', 'LIKE', "%{$keyword}%")
                    ->orWhere('modelis', 'LIKE', "%{$keyword}%")
                    ->orWhere('vin_kodas', 'LIKE', "%{$keyword}%");
            })
            ->orWhereHas('mechanic', function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('specialization', 'LIKE', "%{$keyword}%");
            })
            ->orWhereHas('service', function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            })
            ->orWhereHas('part', function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            })
            ->get();
    }

    /**
     * Filtravimas pagal įvairius kriterijus
     * Elementų atranka (filtravimas) kolekcijoje
     */
    public function filterOrders(array $filters = []): Collection
    {
        return Order::query()
            ->when(isset($filters['status']), function($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['mechanic_id']), function($query) use ($filters) {
                $query->where('mechanic_id', $filters['mechanic_id']);
            })
            ->when(isset($filters['car_id']), function($query) use ($filters) {
                $query->where('car_id', $filters['car_id']);
            })
            ->when(isset($filters['service_id']), function($query) use ($filters) {
                $query->where('service_id', $filters['service_id']);
            })
            ->when(isset($filters['date_from']), function($query) use ($filters) {
                $query->where('created_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function($query) use ($filters) {
                $query->where('created_at', '<=', $filters['date_to']);
            })
            ->when(isset($filters['price_min']), function($query) use ($filters) {
                $query->where('total_price', '>=', $filters['price_min']);
            })
            ->when(isset($filters['price_max']), function($query) use ($filters) {
                $query->where('total_price', '<=', $filters['price_max']);
            })
            ->when(isset($filters['completion_from']), function($query) use ($filters) {
                $query->where('completion_date', '>=', $filters['completion_from']);
            })
            ->when(isset($filters['completion_to']), function($query) use ($filters) {
                $query->where('completion_date', '<=', $filters['completion_to']);
            })
            ->get();
    }

    /**
     * Rūšiavimas pagal pasirinktą lauką
     * Elementų rūšiavimas kolekcijoje
     */
    public function sortOrders(string $column = 'created_at', string $direction = 'desc'): Collection
    {
        $validColumns = ['id', 'car_id', 'service_id', 'mechanic_id', 'status', 'total_price', 'created_at', 'completion_date'];
        
        if (!in_array($column, $validColumns)) {
            $column = 'created_at';
        }
        
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
        
        return Order::orderBy($column, $direction)->get();
    }
} 
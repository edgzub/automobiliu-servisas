<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Car;
use App\Models\Service;
use App\Models\Mechanic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class SearchService
{
    /**
     * Paieška pagal raktažodį visuose susijusiuose objektuose
     * Elementų paieška kolekcijoje
     */
    public function searchByKeyword(string $keyword): SupportCollection
    {
        return Order::where('description', 'like', "%{$keyword}%")
                    ->orWhereHas('mechanic', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('vehicle', function ($query) use ($keyword) {
                        $query->where('brand', 'like', "%{$keyword}%")
                             ->orWhere('model', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('service', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%")
                             ->orWhere('description', 'like', "%{$keyword}%");
                    })
                    ->get();
    }

    /**
     * Filtravimas pagal įvairius kriterijus
     * Elementų atranka (filtravimas) kolekcijoje
     */
    public function filterOrders(array $filters): SupportCollection
    {
        $query = Order::query();
        
        // Filtras pagal statusą
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // Filtras pagal datą
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        // Filtras pagal kainą
        if (isset($filters['price_from'])) {
            $query->where('total_price', '>=', $filters['price_from']);
        }
        
        if (isset($filters['price_to'])) {
            $query->where('total_price', '<=', $filters['price_to']);
        }
        
        return $query->get();
    }

    /**
     * Rūšiavimas pagal pasirinktą lauką
     * Elementų rūšiavimas kolekcijoje
     */
    public function sortOrders(string $column, string $direction = 'asc'): SupportCollection
    {
        // Patikrinti, ar stulpelis egzistuoja
        $allowedColumns = ['id', 'created_at', 'total_price'];
        
        if (!in_array($column, $allowedColumns)) {
            $column = 'created_at'; // Numatytasis rūšiavimo stulpelis
        }
        
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
        
        return Order::orderBy($column, $direction)->get();
    }
} 
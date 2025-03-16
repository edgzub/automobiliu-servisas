<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SearchService;
use App\Services\ReportingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderApiController extends Controller
{
    protected $searchService;
    protected $reportingService;
    
    public function __construct(SearchService $searchService, ReportingService $reportingService)
    {
        $this->searchService = $searchService;
        $this->reportingService = $reportingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $sortField = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        
        $orders = Order::with(['car', 'service', 'mechanic', 'part'])
            ->orderBy($sortField, $sortDir)
            ->paginate($perPage);
            
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'service_id' => 'required|exists:services,id',
            'mechanic_id' => 'required|exists:mechanics,id',
            'part_id' => 'nullable|exists:parts,id',
            'status' => 'required|string',
            'total_price' => 'required|numeric',
            'description' => 'required|string',
            'completion_date' => 'required|date',
        ]);
        
        $order = Order::create($validated);
        
        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        return response()->json($order->load(['car', 'service', 'mechanic', 'part']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'car_id' => 'sometimes|exists:cars,id',
            'service_id' => 'sometimes|exists:services,id',
            'mechanic_id' => 'sometimes|exists:mechanics,id',
            'part_id' => 'nullable|exists:parts,id',
            'status' => 'sometimes|string',
            'total_price' => 'sometimes|numeric',
            'description' => 'sometimes|string',
            'completion_date' => 'sometimes|date',
        ]);
        
        $order->update($validated);
        
        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Search orders by keyword
     */
    public function search(Request $request): JsonResponse
    {
        $keyword = $request->input('q', '');
        $filters = $request->except('q');
        
        if (!empty($keyword)) {
            $results = $this->searchService->searchByKeyword($keyword);
        } elseif (!empty($filters)) {
            $results = $this->searchService->filterOrders($filters);
        } else {
            $results = Order::with(['car', 'service', 'mechanic', 'part'])->get();
        }
        
        return response()->json($results);
    }
    
    /**
     * Get mechanic workload report
     */
    public function mechanicReport(): JsonResponse
    {
        $report = $this->reportingService->getMechanicWorkload();
        
        return response()->json($report);
    }
    
    /**
     * Get service popularity report
     */
    public function serviceReport(): JsonResponse
    {
        $report = $this->reportingService->getServicePopularity();
        
        return response()->json($report);
    }
} 
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request): JsonResponse
    {
        $startDate = $request->date('start_date', 'Y-m-d')?->startOfDay() ?? now()->startOfMonth();
        $endDate = $request->date('end_date', 'Y-m-d')?->endOfDay() ?? now()->endOfDay();

        $query = Order::query()
            ->whereBetween('placed_at', [$startDate, $endDate])
            ->where('status', 'paid');

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->integer('store_id'));
        }

        $totalRevenue = (int) (clone $query)->sum('total_cents');
        $totalOrders = (clone $query)->count();
        $averageOrderValue = $totalOrders > 0 ? (int) round($totalRevenue / $totalOrders) : 0;

        // Group by date
        $dailySales = (clone $query)
            ->select(
                DB::raw('DATE(placed_at) as date'),
                DB::raw('COUNT(id) as orders_count'),
                DB::raw('SUM(total_cents) as total_revenue_cents')
            )
            ->groupBy(DB::raw('DATE(placed_at)'))
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
                'date' => $item->date,
                'orders_count' => (int) $item->orders_count,
                'total_revenue_cents' => (int) $item->total_revenue_cents,
            ]);

        return response()->json([
            'summary' => [
                'total_revenue_cents' => $totalRevenue,
                'total_orders' => $totalOrders,
                'average_order_value_cents' => $averageOrderValue,
            ],
            'daily_sales' => $dailySales,
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $startDate = $request->date('start_date', 'Y-m-d')?->startOfDay() ?? now()->startOfMonth();
        $endDate = $request->date('end_date', 'Y-m-d')?->endOfDay() ?? now()->endOfDay();
        $limit = $request->integer('limit', 10);

        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.placed_at', [$startDate, $endDate])
            ->where('orders.status', 'paid');

        if ($request->filled('store_id')) {
            $query->where('orders.store_id', $request->integer('store_id'));
        }

        $topProducts = $query
            ->select(
                'order_items.product_id',
                'order_items.name_snapshot as name',
                'order_items.sku_snapshot as sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.line_total_cents) as total_revenue_cents')
            )
            ->groupBy('order_items.product_id', 'order_items.name_snapshot', 'order_items.sku_snapshot')
            ->orderByDesc('total_revenue_cents')
            ->limit($limit)
            ->get()
            ->map(fn ($item) => [
                'product_id' => $item->product_id,
                'name' => $item->name,
                'sku' => $item->sku,
                'total_quantity' => (int) $item->total_quantity,
                'total_revenue_cents' => (int) $item->total_revenue_cents,
            ]);

        return response()->json([
            'data' => $topProducts,
        ]);
    }

    public function stores(Request $request): JsonResponse
    {
        $startDate = $request->date('start_date', 'Y-m-d')?->startOfDay() ?? now()->startOfMonth();
        $endDate = $request->date('end_date', 'Y-m-d')?->endOfDay() ?? now()->endOfDay();

        $storePerformance = DB::table('stores')
            ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
                $join->on('stores.id', '=', 'orders.store_id')
                    ->whereBetween('orders.placed_at', [$startDate, $endDate])
                    ->where('orders.status', 'paid');
            })
            ->select(
                'stores.id',
                'stores.name',
                'stores.code',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('COALESCE(SUM(orders.total_cents), 0) as total_revenue_cents')
            )
            ->groupBy('stores.id', 'stores.name', 'stores.code')
            ->orderByDesc('total_revenue_cents')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'total_orders' => (int) $item->total_orders,
                'total_revenue_cents' => (int) $item->total_revenue_cents,
            ]);

        return response()->json([
            'data' => $storePerformance,
        ]);
    }
}

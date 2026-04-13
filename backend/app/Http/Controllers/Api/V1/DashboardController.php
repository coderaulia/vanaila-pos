<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function cashier(): JsonResponse
    {
        $todaysOrders = Order::query()->whereDate('placed_at', today());

        return response()->json([
            'store_count' => Store::query()->count(),
            'open_tickets' => (clone $todaysOrders)->where('status', 'open')->count(),
            'paid_orders' => (clone $todaysOrders)->where('status', 'paid')->count(),
            'sales_cents' => (int) (clone $todaysOrders)->sum('total_cents'),
        ]);
    }

    public function admin(): JsonResponse
    {
        return response()->json([
            'products_active' => Product::query()->where('is_active', true)->count(),
            'low_stock_products' => Product::query()->where('stock_quantity', '<=', 10)->count(),
            'todays_revenue_cents' => (int) Order::query()->whereDate('placed_at', today())->sum('total_cents'),
            'orders_today' => Order::query()->whereDate('placed_at', today())->count(),
        ]);
    }

    public function superadmin(): JsonResponse
    {
        return response()->json([
            'stores_total' => Store::query()->count(),
            'admins_total' => User::query()->where('role', 'admin')->count(),
            'cashiers_total' => User::query()->where('role', 'cashier')->count(),
            'monthly_revenue_cents' => (int) Order::query()
                ->whereBetween('placed_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('total_cents'),
        ]);
    }
}

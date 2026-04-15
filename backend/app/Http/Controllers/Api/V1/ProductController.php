<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::query()
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(function ($builder) use ($request) {
                    $builder
                        ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                        ->orWhere('sku', 'like', '%'.$request->string('search')->toString().'%');
                })
            )
            ->when($request->filled('store_id'), fn ($query) => $query->where('store_id', $request->integer('store_id')))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 12));

        return ProductResource::collection($products)->response();
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        return ProductResource::make($product)->response();
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $validated = $request->validated();

        $product = Product::query()->create($validated);

        return ProductResource::make($product)->response()->setStatusCode(201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validated();

        $product->update($validated);

        return ProductResource::make($product->fresh())->response();
    }
}

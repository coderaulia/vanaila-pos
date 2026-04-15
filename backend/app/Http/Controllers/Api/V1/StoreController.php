<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Store::class);

        return StoreResource::collection(Store::query()->latest()->get())->response();
    }

    public function show(Store $store): JsonResponse
    {
        $this->authorize('view', $store);

        return StoreResource::make($store->loadCount(['products', 'orders']))->response();
    }

    public function store(StoreStoreRequest $request): JsonResponse
    {
        $this->authorize('create', Store::class);

        $validated = $request->validated();

        $store = Store::query()->create($validated);

        return StoreResource::make($store)->response()->setStatusCode(201);
    }

    public function update(UpdateStoreRequest $request, Store $store): JsonResponse
    {
        $this->authorize('update', $store);

        $validated = $request->validated();

        $store->update($validated);

        return StoreResource::make($store->fresh())->response();
    }
}

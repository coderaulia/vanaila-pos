<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'store_id' => $this->store_id,
            'cashier_id' => $this->cashier_id,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'subtotal_cents' => $this->subtotal_cents,
            'tax_cents' => $this->tax_cents,
            'discount_cents' => $this->discount_cents,
            'total_cents' => $this->total_cents,
            'notes' => $this->notes,
            'placed_at' => $this->placed_at?->toIso8601String(),
            'paid_at' => $this->paid_at?->toIso8601String(),
            'store' => StoreResource::make($this->whenLoaded('store')),
            'cashier' => UserResource::make($this->whenLoaded('cashier')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

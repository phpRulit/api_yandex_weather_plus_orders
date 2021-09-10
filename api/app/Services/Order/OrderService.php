<?php

namespace App\Services\Order;

use App\Http\Requests\QuantityRequest;
use App\Http\Requests\ChangePartnerRequest;
use App\Http\Requests\EditOrderRequest;
use App\Models\Other\Order;
use App\Models\Other\OrderProduct;
use App\Models\Other\Product;

class OrderService
{

    public function editPartner(Order $order, ChangePartnerRequest $request):void
    {
        if ($order->partner_id !== $request['partner_id'])
        $order->update(['partner_id' => $request['partner_id']]);
    }

    public function editOrder(Order $order, EditOrderRequest $request): void
    {
        if (isset($request['status']) && $order->status !== $request['status']) {
            $order->update(['status' => $request['status']]);
            if ($request['status'] === Order::STATUS_COMPLETED) {
                $this->sentMailAllNeeded();
            }
        } elseif (isset($request['client_email']) && $order->client_email !== $request['client_email']) {
            $order->update(['client_email' => $request['client_email']]);
        } elseif (isset($request['delivery_dt']) && $order->delivery_dt !== $request['delivery_dt']) {
            $order->update(['delivery_dt' => $request['delivery_dt']]);
        }
    }

    private function sentMailAllNeeded() {
        //пока не сделано...
    }

    public function addItemInOrder(Order $order, Product $product, QuantityRequest $request): OrderProduct
    {
        return OrderProduct::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request['quantity'],
            'price' => $request['quantity'] * $product->price,
        ]);
    }

    public function editQuantityItem(OrderProduct $order_product, Product $product, QuantityRequest $request)
    {
        $order_product->update([
            'quantity' => $request['quantity'],
            'price' => $request['quantity'] * $product->price
        ]);
    }

    public function destroyItemOrder(OrderProduct $order_product): void
    {
        $order_product->delete();
    }

}

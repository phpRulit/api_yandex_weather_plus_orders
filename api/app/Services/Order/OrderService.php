<?php

namespace App\Services\Order;

use App\Http\Requests\QuantityRequest;
use App\Http\Requests\ChangePartnerRequest;
use App\Http\Requests\EditOrderRequest;
use App\Models\Other\Order;
use App\Models\Other\OrderProduct;
use App\Models\Other\Product;
use App\Services\AppMessenger\AppMessenger;

class OrderService
{

    public function editPartner(Order $order, ChangePartnerRequest $request):void
    {
        if ($order->partner_id !== $request['partner_id'])
        $order->update(['partner_id' => $request['partner_id']]);
    }

    public function editOrder(Order $order, EditOrderRequest $request): void
    {
        if (isset($request['status']) && $order->status !== (int)$request['status']) {
            $order->update(['status' => (int)$request['status']]);
        } elseif (isset($request['client_email']) && $order->client_email !== $request['client_email']) {
            $order->update(['client_email' => $request['client_email']]);
        } elseif (isset($request['delivery_dt']) && $order->delivery_dt !== $request['delivery_dt']) {
            $order->update(['delivery_dt' => $request['delivery_dt']]);
        }
    }

    public function sentMailsAboutOrderCompleted(Order $order): void
    {
        $arrayData = [
            ['mail' => $order->client_email, 'name' => ''],
            ['mail' => $order->partner->email, 'name' => $order->partner->name],
        ];
        $vendors = $order->products()
            ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
            ->select('vendors.email', 'vendors.name')->distinct()->get()->toArray();
        foreach ($vendors as $vendor) {
            $arrayData[] = ['mail' => $vendor['email'], 'name' => $vendor['name']];
        }
        $message = [
            'nameRecipient' => null,
            'text' => 'Настоящим сообщаем, что заказ №' . $order->id . ' завершён.',
            'order_products' => $order->orderProducts,
            'products' => $order->products
        ];
        foreach ($arrayData as $data) {
            $message['nameRecipient'] = $data['name'];
            $this->sentMailAllNeeded($data['mail'], $data['name'], $message);
        }
    }

    private function sentMailAllNeeded(string $emailRecipient, string $nameRecipient, array $message): void
    {
        $messenger = new AppMessenger();
        $messenger->toEmail('mail.order')
            ->setSender(env('MAIL_USERNAME'))
            ->setFrom(env("APP_NAME"))
            ->setRecipient($emailRecipient)
            ->setToWhom($nameRecipient)
            ->setSubject('Закрытие заказа.')
            ->setMessageMail($message)
            ->send();
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

    public function editQuantityItem(OrderProduct $order_product, Product $product, QuantityRequest $request): void
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

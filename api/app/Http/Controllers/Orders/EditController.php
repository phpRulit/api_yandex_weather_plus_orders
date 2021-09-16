<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuantityRequest;
use App\Http\Requests\ChangePartnerRequest;
use App\Http\Requests\EditOrderRequest;
use App\Models\Other\Order;
use App\Models\Other\OrderProduct;
use App\Models\Other\Product;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EditController extends Controller
{

    private $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function order(int $order_id): JsonResponse
    {
        return response()->json(Order::where('id', $order_id)->with(['partner', 'orderProducts', 'products'])->first());
    }

    public function editPartner(Order $order, ChangePartnerRequest $request): JsonResponse
    {
        if ($order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ завершён и не подлежит изменениям...'
            ]);
        }
        try {
            $this->service->editPartner($order, $request);
        } catch (\DomainException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'message' => 'Изменения сохранены...'
        ]);
    }

    public function editOrder(Order $order, EditOrderRequest $request): JsonResponse
    {
        if (!isset($request['status']) && $order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ можно будет редактировать, если Вы измените его статус...'
            ]);
        }
        try {
            $this->service->editOrder($order, $request);
        } catch (\DomainException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'message' => 'Изменения сохранены...'
        ]);
    }

    public function sentMailsAboutOrderCompleted (Order $order): void
    {
        if ($order->isCompleted())
            try {
                $this->service->sentMailsAboutOrderCompleted($order);
            } catch (\DomainException $e) {
                Log::error($e->getMessage());
            }
    }

    public function addItemInOrder(Order $order, Product $product, QuantityRequest $request): JsonResponse
    {
        if ($order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ завершён и не подлежит изменениям...'
            ]);
        } elseif ($product->inOrderYet($order)) {
            return response()->json([
                'messageError' => 'Нельзя добавить данный товар, т.к. он уже есть в заказе! Просто измените его количество...'
            ]);
        }
        try {
            $order_product = $this->service->addItemInOrder($order, $product, $request);
        } catch (\DomainException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'order_product' => $order_product,
            'message' => 'Позиция добавлена в заказ...'
        ]);
    }

    public function destroyItemOrder(Order $order, int $order_product_id): JsonResponse
    {
        if ($order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ завершён и не подлежит изменениям...'
            ]);
        }
        $order_product = OrderProduct::where('id', $order_product_id)->first();
        if (is_null($order_product)) {
            return response()->json([
                'messageError' => 'Позиция уже была удалена...'
            ]);
        }
        try {
            $this->service->destroyItemOrder($order_product);
        } catch (\DomainException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'message' => 'Позиция удалена...'
        ]);
    }

    public function editQuantityItem(Order $order, OrderProduct $order_product, Product $product, QuantityRequest $request): JsonResponse
    {
        if ($order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ завершён и не подлежит изменениям...'
            ]);
        }
        try {
            $this->service->editQuantityItem($order_product, $product, $request);
        } catch (\DomainException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'message' => 'Количество изменено...'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuantityRequest;
use App\Http\Requests\ChangePartnerRequest;
use App\Http\Requests\EditOrderRequest;
use App\Models\Other\Order;
use App\Models\Other\OrderProduct;
use App\Models\Other\Product;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EditController extends Controller
{

    private $orderRepository;
    private $productRepository;
    private $orderProductRepository;
    private $orderService;

    public function __construct()
    {
        $this->orderRepository = app(OrderRepository::class);
        $this->productRepository = app(ProductRepository::class);
        $this->orderProductRepository = app(OrderProductRepository::class);
        $this->orderService = app(OrderService::class);
    }

    public function order(int $order_id): JsonResponse
    {
        return response()->json($this->orderRepository->getForShow($order_id));
    }

    public function editPartner(Order $order, ChangePartnerRequest $request): JsonResponse
    {
        if ($order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ завершён и не подлежит изменениям...'
            ]);
        }
        try {
            $this->orderService->editPartner($order, $request);
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
            $this->orderService->editOrder($order, $request);
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

    public function sendMailsAboutOrderCompleted (Order $order): void
    {
        if ($order->isCompleted())
            try {
                $this->orderService->sendMailsAboutOrderCompleted($order);
            } catch (\DomainException $e) {
                Log::error($e->getMessage());
            }
    }

    public function addItemInOrder(Order $order, int $product_id, QuantityRequest $request): JsonResponse
    {
        if ($order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ завершён и не подлежит изменениям...'
            ]);
        }
        $product = $this->productRepository->getById($product_id);
        if (is_null($product)) {
            return response()->json([
                'messageError' => 'Продукт не найден!!! Нельзя добавить в заказ...'
            ]);
        } elseif ($product->inOrderYet($order)) {
            return response()->json([
                'messageError' => 'Нельзя добавить данный товар, т.к. он уже есть в заказе! Просто измените его количество...'
            ]);
        }
        try {
            $order_product = $this->orderService->addItemInOrder($order, $product, $request);
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
        $order_product = $this->orderProductRepository->getById($order_product_id);
        if (is_null($order_product)) {
            return response()->json([
                'messageError' => 'Позиция уже была удалена...'
            ]);
        }
        try {
            $this->orderService->destroyItemOrder($order_product);
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

    public function editQuantityItem(Order $order, OrderProduct $order_product, int $product_id, QuantityRequest $request): JsonResponse
    {
        if ($order->isCompleted()) {
            return response()->json([
                'messageError' => 'Заказ завершён и не подлежит изменениям...'
            ]);
        }
        $product = $this->productRepository->getById($product_id);
        if (is_null($product)) {
            return response()->json([
                'messageError' => 'Продукт не найден!!! Удалите запись...'
            ]);
        }
        try {
            $this->orderService->editQuantityItem($order_product, $product, $request);
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
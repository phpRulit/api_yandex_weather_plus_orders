<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Models\Other\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    public function index(FilterRequest $request): JsonResponse
    {
        $query = Order::with(['partner:id,name', 'orderProducts:id,order_id,quantity,price', 'products:products.name,products.price']);

        if (!empty($request['status'])) {
            if ($request['status'] === 'all') {
                $query->orderByDesc('created_at');
            } elseif ($request['status'] === 'overdue') {
                $query->where('status', Order::STATUS_CONFIRM)
                    ->orderByDesc('delivery_dt');
            } elseif ($request['status'] === 'current') {
                $query->where('status', Order::STATUS_CONFIRM)
                    ->where('delivery_dt', '>=', Carbon::now()->subDay())
                    ->orderBy('delivery_dt');
            } elseif ($request['status'] === 'new') {
                $query->where('status', Order::STATUS_NEW)
                    ->where('delivery_dt', '<', Carbon::now()->subDay())
                    ->orderBy('delivery_dt');;
            } elseif ($request['status'] === 'completed') {
                $query->where('status', Order::STATUS_COMPLETED)
                    ->where('delivery_dt', '>', Carbon::today())
                    ->orderByDesc('delivery_dt');
            }
        }

        return response()->json($query->select(['id', 'status', 'partner_id'])->has('orderProducts')->paginate($request['max'])); //для infinite scroll делаем по-другому...
    }
}

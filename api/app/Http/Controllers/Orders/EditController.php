<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Other\Order;
use App\Models\Other\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function order($order_id): JsonResponse
    {
        return response()->json(Order::where('id', $order_id)->with(['partner', 'orderProducts', 'products'])->first());
    }

    public function getPartnersList(): JsonResponse
    {
        return response()->json(Partner::all());
    }

    public function edit(Order $order, Request $request): JsonResponse
    {

    }

    public function editPartner(Partner $partner, Request $request)
    {

    }

}

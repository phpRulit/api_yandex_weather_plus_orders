<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Other\Partner;
use App\Models\Other\Product;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{

    //вытаскиваем так, если записей немного, если много, то изобретаем другой вариант
    //(сделано для быстроты решения)

    public function getPartnersList(): JsonResponse
    {
        return response()->json(Partner::all());
    }

    public function getProductsList(): JsonResponse
    {
        return response()->json(Product::all());
    }

}

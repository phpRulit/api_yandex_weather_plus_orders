<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Other\Partner;
use App\Models\Other\Product;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{

    //вытаскиваем так, если записей немного, если много, то изобретаем другой вариант,
    //а точнее вносим изменения в миграции, прописываем fulltext search и так же вносим изменения
    //на фронте, ну и здесь соответственно...

    public function getPartnersList(): JsonResponse
    {
        return response()->json(Partner::select(['id', 'email', 'name'])->toBase()->get());
    }

    public function getProductsList(): JsonResponse
    {
        return response()->json(Product::select(['id', 'name', 'price'])->toBase()->get());
    }

}

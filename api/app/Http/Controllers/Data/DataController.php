<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Repositories\PartnerRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{
    private $productRepository;
    private $partnerRepository;

    public function __construct()
    {
        $this->productRepository = app(ProductRepository::class);
        $this->partnerRepository = app(PartnerRepository::class);
    }

    //вытаскиваем так, если записей немного, если много, то изобретаем другой вариант,
    //а точнее вносим изменения в миграции, прописываем fulltext search и так же вносим изменения
    //на фронте, ну и здесь соответственно...

    public function getPartnersList(): JsonResponse
    {
        return response()->json($this->partnerRepository->getList());
    }

    public function getProductsList(): JsonResponse
    {
        return response()->json($this->productRepository->getList());
    }

}

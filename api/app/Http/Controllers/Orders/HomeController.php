<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    private $orderRepository;

    public function __construct()
    {
        $this->orderRepository = app(OrderRepository::class);
    }

    public function index(FilterRequest $request): JsonResponse
    {
        return response()->json($this->orderRepository->getAllWithPaginate($request));
    }
}

<?php

namespace App\Repositories;

use App\Http\Requests\FilterRequest;
use App\Models\Other\Order as Model;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function getAllWithPaginate(FilterRequest $request): LengthAwarePaginator
    {
        $query = $this->startConditions()
            ->with(['partner:id,name', 'orderProducts:id,order_id,quantity,price', 'products:products.name,products.price']);

        if (!empty($request['status'])) {
            if ($request['status'] === 'all') {
                $query->orderByDesc('created_at');
            } elseif ($request['status'] === 'overdue') {
                $query->where('status', Model::STATUS_CONFIRM)
                    ->orderByDesc('delivery_dt');
            } elseif ($request['status'] === 'current') {
                $query->where('status', Model::STATUS_CONFIRM)
                    ->where('delivery_dt', '>=', Carbon::now()->subDay())
                    ->orderBy('delivery_dt');
            } elseif ($request['status'] === 'new') {
                $query->where('status', Model::STATUS_NEW)
                    ->where('delivery_dt', '<', Carbon::now()->subDay())
                    ->orderBy('delivery_dt');;
            } elseif ($request['status'] === 'completed') {
                $query->where('status', Model::STATUS_COMPLETED)
                    ->where('delivery_dt', '>=', Carbon::today())
                    ->orderByDesc('delivery_dt');
            }
        }

        return $query->select(['id', 'status', 'partner_id'])->has('orderProducts')->paginate($request['max']);
    }

    public function getForShow($id): Model
    {
        return $this->startConditions()
            ->where('id', $id)
            ->with(['partner:id,name,email', 'orderProducts:id,order_id,product_id,quantity,price', 'products:products.id,products.name,products.price'])
            ->select(['id', 'status', 'client_email', 'partner_id', 'delivery_dt'])
            ->first();
    }


    public function getById(int $id): Model
    {
        return $this->startConditions()->find($id);
    }

}

<?php

namespace App\Repositories;

use App\Models\Other\OrderProduct as Model;

class OrderProductRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function getById(int $id): Model
    {
        return $this->startConditions()->find($id);
    }

}

<?php

namespace App\Repositories;

use App\Models\Other\Product as Model;
use Illuminate\Support\Collection;

class ProductRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function getList(): Collection
    {
        return $this->startConditions()->select(['id', 'name', 'price'])->toBase()->get();
    }

}

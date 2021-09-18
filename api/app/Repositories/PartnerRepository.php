<?php

namespace App\Repositories;

use App\Models\Other\Partner as Model;
use Illuminate\Support\Collection;

class PartnerRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function getList(): Collection
    {
        return $this->startConditions()->select(['id', 'email', 'name'])->toBase()->get();
    }

}

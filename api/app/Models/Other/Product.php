<?php

namespace App\Models\Other;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $vendor_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

//ТОВАР
class Product extends Model
{
    protected $guarded = ['id'];

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class, 'vendor_id', 'id');
    }
}

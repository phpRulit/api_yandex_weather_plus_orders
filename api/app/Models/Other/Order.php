<?php

namespace App\Models\Other;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


/**
 * @property int $id
 * @property int $status
 * @property string $client_email
 * @property int $partner_id
 * @property Carbon $delivery_dt
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

//ЗАКАЗ
class Order extends Model
{

    const STATUS_NEW = 0;
    const STATUS_CONFIRM = 10;
    const STATUS_COMPLETED = 20;

    protected $guarded = ['id'];


    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            OrderProduct::class,
            'order_id',
            'id',
            'id',
            'product_id'
        );
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }


}

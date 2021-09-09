<?php

namespace App\Models\Other;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

//ПОСТАВЩИК
class Vendor extends Model
{
    protected $guarded = ['id'];
}

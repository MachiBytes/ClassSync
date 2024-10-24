<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use UsesUuid;

    public $incrementing = false;
    protected $keyType = 'uuid';
}

<?php

namespace App\Models;

use App\Traits\UsesUuid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hub extends BaseModel
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'name',
        'owner_id'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

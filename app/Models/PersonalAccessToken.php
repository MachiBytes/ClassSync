<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use App\Traits\UsesUuid;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use UsesUuid;
}

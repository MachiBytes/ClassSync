<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\VerificationEmailDesigner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\UsesUuid;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, UsesUuid;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'birthdate'
    ];

    protected $attributes = [
        'phone_number' => ''
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function login()
    {
        if ($this->email_verified_at == null) {
            VerificationEmailDesigner::sendVerificationEmail($this);
            throw new Exception("Email is not verified. Please verify your email first through the email sent to you.");
        }

        return $this->createToken('API TOKEN')->plainTextToken;
    }

    public function hubs(): HasMany
    {
        return $this->hasMany(Hub::class);
    }
}

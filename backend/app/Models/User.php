<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => Role::class,
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function supervisor(): HasOne
    {
        return $this->hasOne(Supervisor::class);
    }

    public function platformNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PlatformNotification::class);
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role instanceof Role ? $this->role->value : $this->role,
        ];
    }

    public function hasRole(Role|string $role): bool
    {
        $value = $role instanceof Role ? $role->value : $role;

        return $this->role->value === $value;
    }
}

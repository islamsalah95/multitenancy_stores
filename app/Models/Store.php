<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Services\TenantDatabaseService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Contracts\IsTenant as TenantContract;
use Spatie\Multitenancy\Models\Concerns\ImplementsTenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Store extends Authenticatable implements MustVerifyEmail, TenantContract
{
    use HasApiTokens, HasFactory, Notifiable, UsesTenantConnection, ImplementsTenant;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'description',
        'logo',
        'address',
        'is_active',
        'database',
        'domain',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->domain)) {
                $store->domain = Str::slug($store->name) . '.localhost';
            }

            if (empty($store->database)) {
                $store->database = 'tenant_' . Str::slug($store->name, '_');
            }
        });

        static::created(function ($store) {
            $tenantService = app(TenantDatabaseService::class);
            $tenantService->setupTenant($store);
        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Authentication methods
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }

    public function getDatabaseName(): string
    {
        return $this->database ?? 'tenant_' . $this->id;
    }



    /**
     * Get the domain for tenant resolution
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Check if this store matches the given domain
     */
    public function matchesDomain(string $domain): bool
    {
        return $this->domain === $domain;
    }
}

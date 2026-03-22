<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\AdminRole;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_name',
        'is_approved',
        'phone',
        'address',
        'google_id',
        'city_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'role_name' => AdminRole::class,
        ];
    }

     /**
     * Get the user role.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array($this->role_name, AdminRole::cases());
    }

    public function isSuperAdmin(): bool
    {
        return $this->role_name === AdminRole::SUPER_ADMIN;
    }

    public function isSalesAdmin(): bool
    {
        return $this->role_name === AdminRole::SALES_ADMIN;
    }

    public function isAccountingAdmin(): bool
    {
        return $this->role_name === AdminRole::ACCOUNTING_ADMIN;
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function orders(){
        return $this->morphMany(Order::class , 'customer');
    }

    public function createdOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'admin_creator_id');
    }

    public function createdCoupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'created_by');
    }

    

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && ($this->isSuperAdmin() || $this->isApproved());
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function totalTransactions()
    {
        return $this->transaction()->count();
    }

    public function createWallet()
    {
        if ($this->hasWallet()) {
            return $this->wallet();
        }

        return $this->wallet()->create();
    }

    public function hasWallet()
    {
        return $this->wallet && $this->wallet->id;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isChecker()
    {
        return $this->role === 'checker' || $this->role === 'admin';
    }

    public function isMaker()
    {
        return $this->role === 'maker';
    }

    public function setUserAsChecker()
    {
        $this->role = 'checker';
    }

    public function scopeMaker(Builder $query)
    {
        return $query->where('role', 'maker');
    }
}

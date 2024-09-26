<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role() : BelongsTo
    {
        return $this->belongsTo( Role::class );
    }

    public function transactions() : HasMany
    {
        return $this->hasMany( Transaction::class );
    }

    public function wallet() : HasOne
    {
        return $this->hasOne( Wallet::class );
    }

    public function creditBalance() : float
    {
       return $this->transactions()->where('approval_status', 'approved')->where('type', 'credit')->sum('amount');
    }

    public function debitBalance() : float
    {
       return $this->transactions()->where('approval_status', 'approved')->where('type', 'debit')->sum('amount');
    }

    public function updateWalletBalance() : float
    {
        return $this->wallet()->update( ['balance' =>  $this->walletBalance() ] );
    }

    public function walletBalance() : float
    {
        return $this->creditBalance() - $this->debitBalance();
    }

    public function balance() : float
    {
        return $this->wallet?->balance;
    }

    public function getIsMarkerAttribute()
    {
        return $this->role->name === 'marker';
    }

    public function getIsCheckerAttribute()
    {
        return $this->role->name === 'checker';
    }
}

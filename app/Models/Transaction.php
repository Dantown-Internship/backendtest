<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'amount',
    ];

    protected $guarded = ['status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionActivity()
    {
        return $this->hasMany(TransactionActivity::class);
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function approve()
    {
        return $this->status = 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function reject()
    {
        return $this->status = 'rejected';
    }

    public function scopeApproved(Builder $query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected(Builder $query)
    {
        return $query->where('status', 'rejected');
    }

    public function getFormattedAmountAttribute()
    {
        return Money::NGN($this->amount, true);
    }
}

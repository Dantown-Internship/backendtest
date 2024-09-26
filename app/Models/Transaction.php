<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'description', 'amount', 'approval_status'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditBalance() : float
    {
        return $this->where('approval_status', 'approved')->where('type', 'credit')->sum('amount');
    }

    public function debitBalance() : float
    {
        return $this->where('approval_status', 'approved')->where('type', 'debit')->sum('amount');
    }

    public function getIsApprovedAttribute()
    {
        return $this->approval_status === 'approved';
    }

    public function getIsRejectedAttribute()
    {
        return $this->approval_status === 'rejected';
    }

    public function getIsPendingAttribute()
    {
        return $this->approval_status === 'pending';
    }

    public function scopeBalance() : float
    {
        return $this->creditBalance() - $this->debitBalance();
    }

}

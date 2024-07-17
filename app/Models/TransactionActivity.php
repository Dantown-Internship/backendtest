<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'activity',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }
}

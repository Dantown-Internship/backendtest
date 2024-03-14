<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // banking uses credit to add to balance and debit to deduct from balance
    // accounting uses debit to add to balance and credit to deduct from balance
    public function creditBalance(float $amount)
    {
        $this->increment('balance', $amount);
        
        Log::alert("User {$this->user->name} Wallet credited");
    }

    public function debitBalance(float $amount)
    {
        $this->decrement('balance', $amount);
        
        Log::alert("User {$this->user->name} Wallet debited");
    }
}

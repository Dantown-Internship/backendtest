<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemPool extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'balance'];
    
    public function creditBalance(float $amount)
    {
        $this->increment('balance', $amount);
        
        Log::alert("System pool credited");
    }

    public function debitBalance(float $amount)
    {
        $this->decrement('balance', $amount);
        
        Log::alert("System pool debited");
    }
}

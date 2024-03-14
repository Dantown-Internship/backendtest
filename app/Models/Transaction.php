<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'third_party', 'amount', 'type', 'description', 'status', 'note'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatus($status)
    {
        $this->update(['status' => $status]);
    }

    public function updateNote($note)
    {
        $this->update(['note' => $note]);
    }
}

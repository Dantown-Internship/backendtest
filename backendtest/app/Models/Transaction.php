<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = [];

    protected static $logAttributes = ['type','description','amount','status','rejection_reason','approved_id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type','description','amount','status','rejection_reason','approved_id'])
            ->setDescriptionForEvent(fn(string $eventName) => "Transaction: {$eventName}")
            ->logOnlyDirty();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_id');
    }

}

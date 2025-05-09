<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'service_id',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function customerQueues()
    {
        return $this->hasMany(CustomerQueue::class);
    }

    public function queueRules()
    {
        return $this->hasOne(QueueRule::class);
    }
}

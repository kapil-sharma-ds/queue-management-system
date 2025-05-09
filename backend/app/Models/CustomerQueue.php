<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'service_id',
        'counter_id',
        'queue_number',
        'status',
        'joined_at',
        'called_at',
        'served_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'called_at' => 'datetime',
        'served_at' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}


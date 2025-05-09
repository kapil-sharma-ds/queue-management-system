<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'max_wait_time',
        'auto_skip_time',
        'notify_before',
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

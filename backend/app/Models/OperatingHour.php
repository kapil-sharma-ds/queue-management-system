<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'day_of_week',
        'opens_at',
        'closes_at',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}


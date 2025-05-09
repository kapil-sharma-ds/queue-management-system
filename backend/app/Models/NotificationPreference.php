<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'notify_on_new',
        'notify_on_upcoming',
        'notify_on_skip',
        'notify_via_sms',
        'notify_via_email',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}


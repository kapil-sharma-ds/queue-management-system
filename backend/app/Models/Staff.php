<?php

namespace App\Models;

use App\Services\RedisSearchService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'bio',
        'password',
        'role_id',
        'service_id',
        'counter_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function searchableFields(): array
    {
        return ['name', 'email'];
    }

    protected static function booted()
    {
        static::updated(function ($staff) {
            (new RedisSearchService(
                model: $staff,
                key: 'staff',
                query: '',
                searchableFields: ['name', 'email']
            ))->updateItem($staff);
        });

        static::deleted(function ($staff) {
            (new RedisSearchService(
                model: $staff,
                key: 'staff',
                query: '',
                searchableFields: ['name', 'email']
            ))->deleteItem($staff);
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }
}


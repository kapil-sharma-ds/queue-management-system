<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Staff extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'service_id',
        'counter_id',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Used for Laravel Scout based searching.
     * This method is used to convert the model instance into an array.
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,         // Index the 'name' attribute
            'email' => $this->email,       // Index the 'email' attribute
        ];
    }

    public function searchableFields(): array
    {
        return ['name', 'email'];
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


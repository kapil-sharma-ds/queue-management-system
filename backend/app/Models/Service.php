<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['counters'];

    public function counters(): HasMany
    {
        return $this->hasMany(Counter::class);
    }

    public function operatingHours()
    {
        return $this->hasMany(OperatingHour::class);
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

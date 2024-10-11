<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'eligibility',
        'requirements',
        'numberOfRequirements',
        'status',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
    public static $serviceExamples = [
        'Burial Assistance',
        'Calamity Assistance',
        'Educational Assistance',
        'Medical Billing Assistance',
        'Transportation Assistance',
        'Solicitation',
    ];

    public static $serviceStatus = [
        'Available',
        'Unavailable',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'type',
        'details',
    ];

    /**
     * Define relationship with Service.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public static function getRequirementTypes(): array
    {
        return [
            'Letter',
            'ID (Identification)',
            'Government/Legal Clearance',
            'Government/Legal Certificate',
            'Employment/Professional Document',
            'Financial Document',
            'Educational Document',
            'Utility/Address Verification',
            'Medical Record',
        ];
    }
}

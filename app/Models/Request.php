<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Request extends Model
{

    use HasFactory;

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    protected $fillable = [
        'name',
        'address',
        'contact',
        'email',
        'status',
        'message',
        'tracking_no',
        'files_path',
        'service_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {

            if (empty($request->status)) {
                $request->status = "For review";
            }
        });
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('address', 'like', '%' . $keyword . '%')
                ->orWhere('contact', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        });
    }
}

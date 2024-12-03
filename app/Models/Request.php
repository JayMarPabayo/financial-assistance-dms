<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Request extends Model
{

    use HasFactory;

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(Schedule::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'name_extension',
        'deceased_person',
        'gender',
        'birthdate',
        'municipality',
        'address',
        'contact',
        'email',
        'status',
        'message',
        'tracking_no',
        'service_id',
    ];

    public static $requestStatus = [
        'For schedule',
        'Rejected',
        'Approved',
    ];

    public static $nameExtensions = [
        'Jr.',
        'Sr.',
        'III',
        'IV',
        'V',
        'VI',
    ];

    public static $municipalities = [
        'Alubijid',
        'Balingasag',
        'Balingoan',
        'Binuangan',
        'Claveria',
        'El Salvador',
        'Gingoog',
        'Gitagum',
        'Initao',
        'Jasaan',
        'Kinoguitan',
        'Lagonglong',
        'Laguindingan',
        'Libertad',
        'Lugait',
        'Magsaysay',
        'Manticao',
        'Medina',
        'Naawan',
        'Opol',
        'Salay',
        'Sugbongcogon',
        'Tagoloan',
        'Talisayan',
        'Villanueva',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {

            if (empty($request->status)) {
                $request->status = "For review";
            }

            if (empty($request->tracking_no)) {
                $request->tracking_no = uniqid();
            }
        });
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('firstname', 'like', '%' . $keyword . '%')
                ->orWhere('middlename', 'like', '%' . $keyword . '%')
                ->orWhere('lastname', 'like', '%' . $keyword . '%')
                ->orWhere('address', 'like', '%' . $keyword . '%')
                ->orWhere('contact', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        });
    }

    public function fullName(): string
    {
        $middleInitial = $this->middlename ? strtoupper(substr($this->middlename, 0, 1)) . '.' : '';
        return trim(Str::title("{$this->lastname}, {$this->firstname} {$middleInitial}"));
    }
}

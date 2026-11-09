<?php

namespace App\Models;

use App\Support\Constants;
use Database\Factories\RestaurantFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Restaurant extends Model
{
    /** @use HasFactory<RestaurantFactory> */
    use HasFactory, HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip',
        'logo_url',
        'website_url',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function statusLabel(): string
    {
        return Constants::RESTAURANT_STATUS[$this->status] ?? $this->status;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;

class Request extends Model
{
    // Константы для статусов
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    // Константы для расчетов
    private const EARTH_RADIUS_KM = 6371;
    private const COORDINATE_PRECISION = 6;

    protected $fillable = [
        'user_id',
        'title', 
        'category',
        'description',
        'address',
        'latitude',
        'longitude',
        'status', 
        'deadline',
        'estimated_time',
        'contact_phone',
        'urgency',
        'files'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'files' => 'array',
        'estimated_time' => 'integer'
    ];

    /**
     * Отношения
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Атрибуты
     */
    protected function latitude(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (float) $value,
            set: fn ($value) => round($value, self::COORDINATE_PRECISION)
        );
    }

    protected function longitude(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (float) $value,
            set: fn ($value) => round($value, self::COORDINATE_PRECISION)
        );
    }

    /**
     * Методы для работы с геолокацией
     */
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function distanceTo(Request $otherRequest): float
    {
        if (!$this->hasCoordinates() || !$otherRequest->hasCoordinates()) {
            return PHP_FLOAT_MAX;
        }

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($otherRequest->latitude);
        $lonTo = deg2rad($otherRequest->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(
            sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
            )
        );

        return $angle * self::EARTH_RADIUS_KM;
    }

    /**
     * Поиск ближайших запросов в радиусе
     * 
     * @param float $latitude Широта точки поиска
     * @param float $longitude Долгота точки поиска
     * @param int $radius Радиус поиска в километрах
     * @return Collection
     */
    public static function findNearby(
        float $latitude,
        float $longitude,
        int $radius = 10
    ): Collection {
        return self::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw(
                '*, ( ' . self::EARTH_RADIUS_KM . ' * acos( cos( radians(?) ) * ' .
                'cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + ' .
                'sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }

    /**
     * Вспомогательные методы
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
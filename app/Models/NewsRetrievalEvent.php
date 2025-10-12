<?php

namespace App\Models;

use App\Enums\NewsRetrievalEventStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsRetrievalEvent extends Model
{
    protected $guarded = ['id'];

    /**
     * Casts fields.
     *
     * @var array
     */
    protected $casts = [
        'status' => NewsRetrievalEventStatus::class,
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Fetch events for provided source.
     *
     * @param Builder $builder
     * @param string $source
     * @return Builder
     */
    public function scopeFor(Builder $builder, string $source): Builder
    {
        return $builder->where('source', $source);
    }

    /**
     * Retrieval attempt relationship.
     *
     * @return HasMany
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(NewsRetrievalAttempt::class, 'event_id');
    }

    /**
     * Determine whether attempt was successful.
     *
     * @return boolean
     */
    public function successful(): bool
    {
        return $this->status === NewsRetrievalEventStatus::COMPLETED->value;
    }

    /**
     * Determine whether attempt was unsuccessful.
     *
     * @return boolean
     */
    public function unsuccessful(): bool
    {
        return ! $this->successful();
    }

    /**
     * Set attempt completed.
     *
     * @return void
     */
    public function setCompleted(): void
    {
        $this->status = NewsRetrievalEventStatus::COMPLETED->value;
        $this->completed_at = now();

        $this->save();
    }

    /**
     * Set attempt failed.
     *
     * @return void
     */
    public function setFailed(): void
    {
        $this->status = NewsRetrievalEventStatus::FAILED->value;
        $this->failed_at = now();

        $this->save();
    }
}

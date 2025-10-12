<?php

namespace App\Models;

use App\Enums\NewsRetrievalAttemptStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Client\Response;

class NewsRetrievalAttempt extends Model
{
    protected $guarded = ['id'];

    /**
     * Casts attributes.
     *
     * @var array
     */
    protected $casts = [
        'status' => NewsRetrievalAttemptStatus::class,
        'retrieve_from' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Event relationship.
     *
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(NewsRetrievalEvent::class, 'event_id');
    }

    /**
     * Determine whether attempt was successful.
     *
     * @return boolean
     */
    public function successful(): bool
    {
        return $this->status === NewsRetrievalAttemptStatus::COMPLETED->value;
    }

    /**
     * Determine whether attempt wasn't successful.
     *
     * @return boolean
     */
    public function unsuccessful(): bool
    {
        return ! $this->successful();
    }

    /**
     * Set when attempt starts.
     *
     * @return void
     */
    public function setStarted(): void
    {
        $this->status = NewsRetrievalAttemptStatus::STARTED->value;
        $this->started_at = now();

        $this->save();
    }

    /**
     * Set when attempt completes.
     *
     * @param Response $response
     * @return void
     */
    public function setCompleted(Response $response): void
    {
        $this->status = NewsRetrievalAttemptStatus::COMPLETED->value;
        $this->response_code = $response->status();
        $this->completed_at = now();

        $this->save();

        $this->event->setCompleted();
    }

    /**
     * Set when attempt fails.
     *
     * @return void
     */
    public function setFailed(): void
    {
        $this->status = NewsRetrievalAttemptStatus::FAILED->value;
        $this->save();

        $this->event->setFailed();
    }

    /**
     * Get url for attempt.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return "{$this->url}";
    }
}

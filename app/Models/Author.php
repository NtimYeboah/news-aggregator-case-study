<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    /**
     * Fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    
    /**
     * News relationship.
     *
     * @return HasMany
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'source_id');
    }
}

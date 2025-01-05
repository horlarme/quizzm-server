<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Taggable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Taggable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Taggable query()
 *
 * @mixin \Eloquent
 */
class Taggable extends MorphPivot
{
    protected $guarded = [];

    /**
     * @return MorphToMany<Quiz, static>
     */
    public function quizzes(): MorphToMany
    {
        return $this->morphedByMany(Quiz::class, 'taggable');
    }

    protected function casts(): array
    {
        return [
            'attached_at' => 'datetime',
        ];
    }
}

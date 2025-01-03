<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 *
 * @method static \Database\Factories\TagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag query()
 *
 * @mixin \Eloquent
 */
class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function quizzes()
    {
        return $this->morphedByMany(Quiz::class, 'taggable');
    }
}

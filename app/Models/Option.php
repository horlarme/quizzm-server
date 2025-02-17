<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Database\Factories\OptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $question_id
 * @property string $value
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Question $question
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 *
 * @method static \Database\Factories\OptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Option newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Option newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Option query()
 *
 * @mixin \Eloquent
 */
class Option extends Model
{
    /** @use HasFactory<OptionFactory> */
    use HasFactory;

    use HasUlid;

    protected $guarded = [];

    /**
     * @return BelongsTo<Question, self>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }
}

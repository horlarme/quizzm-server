<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $quiz_id
 * @property string $title
 * @property string $option_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Option> $options
 * @property-read int|null $options_count
 * @property-read \App\Models\Quiz $quiz
 *
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Question query()
 *
 * @mixin \Eloquent
 */
class Question extends Model
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory;

    use HasUlid;

    const OptionTypes = [
        self::OptionTypeText,
        self::OptionTypeImage,
    ];

    const OptionTypeImage = 'image';

    const OptionTypeText = 'text';

    /**
     * @return HasMany<Option, self>
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->chaperone();
    }

    /**
     * @return BelongsTo<Quiz, self>
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}

<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Database\Factories\QuestionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $quiz_id
 * @property string $title
 * @property string $option_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Option> $options
 * @property-read int|null $options_count
 * @property-read Quiz $quiz
 *
 * @method static QuestionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Question newModelQuery()
 * @method static Builder<static>|Question newQuery()
 * @method static Builder<static>|Question query()
 *
 * @mixin Eloquent
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

    protected $guarded = [];

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

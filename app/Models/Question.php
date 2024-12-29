<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $quiz_id
 * @property string $title
 * @property string $option_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Option> $options
 * @property-read int|null $options_count
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
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    const OptionTypes = [
        self::OptionTypeText,
        self::OptionTypeImage,
    ];

    const OptionTypeImage = 'image';

    const OptionTypeText = 'text';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Option, self>
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }
}

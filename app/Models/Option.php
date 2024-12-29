<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Database\Factories\OptionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $question_id
 * @property string $value
 * @property bool $is_correct
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Question $question
 *
 * @method static OptionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Option newModelQuery()
 * @method static Builder<static>|Option newQuery()
 * @method static Builder<static>|Option query()
 *
 * @mixin Eloquent
 */
class Option extends Model
{
    /** @use HasFactory<OptionFactory> */
    use HasFactory;

    use HasUlid;

    protected $guarded = [];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * @return BelongsTo<Question, self>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}

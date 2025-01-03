<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $option_id
 * @property int $is_correct
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\Option $option
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\ResultFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Result newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Result newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Result query()
 *
 * @mixin \Eloquent
 */
class Result extends Model
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory;

    use HasUlid;

    public const UPDATED_AT = null;

    protected $guarded = [];

    /**
     * @return BelongsTo<User, static>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Option, static>
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}

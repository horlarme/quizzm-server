<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $quiz_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Quiz $quiz
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\PlayerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Player query()
 *
 * @mixin \Eloquent
 */
class Player extends Model
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory;

    use HasUlid;

    const Statuses = [
        self::StatusApproved,
        self::StatusRejected,
        self::StatusPending,
    ];

    const StatusPending = 'pending';

    const StatusRejected = 'rejected';

    const StatusApproved = 'approved';

    protected $guarded = [];

    /**
     * @return BelongsTo<User, static>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Quiz, static>
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $user_id
 * @property string $quiz_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    const Statuses = [
        self::StatusApproved,
        self::StatusRejected,
        self::StatusPending,
    ];

    const StatusPending = 'pending';

    const StatusRejected = 'rejected';

    const StatusApproved = 'approved';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $user_id
 * @property string $option_id
 * @property \Illuminate\Support\Carbon $created_at
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
}

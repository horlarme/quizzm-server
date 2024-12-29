<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $question_id
 * @property string $value
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
    /** @use HasFactory<\Database\Factories\OptionFactory> */
    use HasFactory;

    protected $casts = [
        'is_correct' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 *
 * @method static \Database\Factories\TagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Tag query()
 *
 * @mixin \Eloquent
 */
class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;
}

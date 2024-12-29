<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Str;

trait HasUlid
{
    use HasUlids;

    protected static function bootHasUlid()
    {
        static::creating(function (self $model) {
            $model->setAttribute('id', Str::ulid()->toString());
        });
    }
}

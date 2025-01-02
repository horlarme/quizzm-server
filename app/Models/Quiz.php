<?php

namespace App\Models;

use Database\Factories\QuizFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $user_id
 * @property string $title
 * @property string $description
 * @property string $thumbnail
 * @property string $status
 * @property bool $require_registration
 * @property bool $require_approval
 * @property string $start_type
 * @property \Illuminate\Support\Carbon|null $start_time
 * @property string $visibility
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Player> $players
 * @property-read int|null $players_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\QuizFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Quiz public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Quiz published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\App\Models\Quiz selectMinimal()
 *
 * @mixin \Eloquent
 */
class Quiz extends Model
{
    /** @uses HasFactory<QuizFactory> */
    use HasFactory;

    use HasUniqueStringIds;

    const StartTypes = [
        self::StartTypeUser,
        self::StartTypeManual,
        self::StartTypeAutomatic,
    ];

    const StartTypeAutomatic = 'automatic';

    const StartTypeManual = 'manual';

    const StartTypeUser = 'user';

    const Visibilities = [
        self::VisibilityPublic,
        self::VisibilityPrivate,
    ];

    const VisibilityPrivate = 'private';

    const VisibilityPublic = 'public';

    const Statuses = [
        self::StatusDraft,
        self::StatusPublished,
        self::StatusArchived,
    ];

    const StatusDraft = 'draft';

    const StatusPublished = 'published';

    const StatusArchived = 'archived';

    protected $guarded = [];

    protected $casts = [
        'require_registration' => 'boolean',
        'require_approval' => 'boolean',
        'start_time' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function (self $model) {
            $model->setAttribute('id', Str::ulid());
        });
    }

    public function isDraft(): bool
    {
        return $this->status === self::StatusDraft;
    }

    public function scopeSelectMinimal(Builder $builder): Builder
    {
        return $builder->select('id', 'title', 'thumbnail', 'visibility', 'created_at', 'user_id')
            ->addSelect(DB::raw('LEFT(description, 100) as description'));
    }

    public function scopePublic(Builder $builder): Builder
    {
        return $builder->where('visibility', Quiz::VisibilityPublic);
    }

    /**
     * @return BelongsTo<User, static>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Question, static>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->inverse();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::StatusPublished);
    }

    public function isPublished(): bool
    {
        return $this->status === self::StatusPublished;
    }

    protected function isValidUniqueId($value): bool
    {
        return true;
    }

    /**
     * @return HasMany<Player, static>
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function isStarted(): bool
    {
        if ($this->start_type === self::StartTypeAutomatic) {
            return $this->start_time?->isPast() ?? false;
        }

        if ($this->start_type === self::StartTypeManual) {
            return (bool) $this->started_at;
        }

        if ($this->start_type === self::StartTypeUser) {
            return ! $this->start_time || $this->start_time->isPast();
        }

        return false;
    }
}

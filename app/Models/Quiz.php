<?php

namespace App\Models;

use Database\Factories\QuizFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
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
 * @property Carbon|null $start_time
 * @property string $visibility
 * @property string|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Question> $questions
 * @property-read int|null $questions_count
 * @property-read User $user
 *
 * @method static QuizFactory factory($count = null, $state = [])
 * @method static Builder<static>|Quiz newModelQuery()
 * @method static Builder<static>|Quiz newQuery()
 * @method static Builder<static>|Quiz public ()
 * @method static Builder<static>|Quiz query()
 * @method static Builder<static>|Quiz selectMinimal()
 *
 * @mixin Eloquent
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
        return $this->hasMany(Question::class);
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
}

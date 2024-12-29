<?php

namespace App\Providers;

use App\Models\Option;
use App\Models\Player;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Tag;
use App\Models\User;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('viewPulse', fn (?User $user) => app()->hasDebugModeEnabled() || $user?->email === 'lawaloladipupo@outlook.com');

        Relation::morphMap([
            'option' => Option::class,
            'player' => Player::class,
            'question' => Question::class,
            'quiz' => Quiz::class,
            'result' => Result::class,
            'tag' => Tag::class,
            'user' => User::class,
        ]);

        JsonResource::withoutWrapping();

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}

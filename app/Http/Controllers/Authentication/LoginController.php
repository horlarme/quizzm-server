<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @tags Authentication
 */
class LoginController extends Controller
{
    /**
     * Oauth Callback
     *
     * Request for a login token with a social media provider.
     *
     * @param  'github'|'google'  $driver  The oauth provider to use. Supports `github` and `google`.
     *
     * @unauthenticated
     */
    public function __invoke(string $driver, Request $request): array
    {
        $this->confirmDriverSupport($driver);

        $request->get('code');

        $user = Socialite::driver($driver)->stateless()->user();
        $nameArray = explode(' ', $user->getName());

        $userInstance = User::query()
            ->firstOrCreate([
                'email' => $user->getEmail(),
            ], [
                'first_name' => $nameArray[0],
                'last_name' => $nameArray[1],
                'avatar' => $user->getAvatar(),
            ]);

        return [
            'user' => new UserResource($userInstance),
            'token' => $userInstance->createToken('api-token')->plainTextToken,
            'new_user' => $userInstance->wasRecentlyCreated,
        ];
    }

    private function confirmDriverSupport($driver): void
    {
        abort_if(! in_array($driver, ['github', 'google']), Response::HTTP_BAD_REQUEST, 'Provided driver is not supported.');
    }

    /**
     * OAuth Redirect
     *
     * Redirect to the social media provider for authentication. This request should not be made through an API request but rather through a web browser as an anchor tag.
     *
     * Example usage: `<a href="//base.url.com/api/auth/github">Login with Github</a>`
     *
     * @param  'github'|'google'  $driver  The oauth provider to use. Supports `github` and `google`.
     *
     * @unauthenticated
     */
    public function redirect(string $driver): RedirectResponse
    {
        $this->confirmDriverSupport($driver);

        return Socialite::driver($driver)->stateless()->redirect();
    }
}

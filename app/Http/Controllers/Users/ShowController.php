<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPublicResource;
use App\Models\User;

/**
 * @tags Users
 */
class ShowController extends Controller
{
    /**
     * Get User Profile
     *
     * Get a user's public profile information.
     */
    public function __invoke(User $user)
    {
        return new UserPublicResource($user);
    }
}

<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

/**
 * @tags Authentication
 */
class CurrentSessionController extends Controller
{
    /**
     * Current Session
     *
     * Get the current user's session information.
     *
     * @return UserResource
     */
    public function __invoke(Request $request)
    {
        return UserResource::make($request->user());
    }
}

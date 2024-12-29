<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @tags Authentication
 */
class LogoutController extends Controller
{
    /**
     * Logout
     *
     * Terminate the user's current session.
     */
    public function __invoke(Request $request): \Illuminate\Http\Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}

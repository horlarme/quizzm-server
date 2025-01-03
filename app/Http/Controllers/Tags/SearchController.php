<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

/**
 * @tags Tags
 */
class SearchController extends Controller
{
    /**
     * Search Tags
     *
     * Search tags by name. Returns paginated list of matching tags.
     *
     * @unauthenticated
     */
    public function __invoke(Request $request)
    {
        return TagResource::collection(
            Tag::query()
                ->when(
                    $request->get('search'),
                    fn ($query, string $search) => $query->where('name', 'like', "%{$search}%")
                )
                ->paginate()
        );
    }
}

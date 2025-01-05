<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

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
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<TagResource>>
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        return TagResource::collection(
            Tag::query()
                ->when(
                    $request->string('search'),
                    fn ($query, string $search) => $query->where('name', 'like', "$search%")
                )
                ->paginate(page: $request->integer('page', 1))
        );
    }
}

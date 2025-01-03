<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\CreateRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;

/**
 * @tags Tags
 */
class CreateController extends Controller
{
    /**
     * Create Tag
     *
     * Create a new tag or return existing one if name already exists.
     */
    public function __invoke(CreateRequest $request)
    {
        $tag = Tag::firstOrCreate([
            'name' => $request->get('name'),
        ]);

        return new TagResource($tag);
    }
}

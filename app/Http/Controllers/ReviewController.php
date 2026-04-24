<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ReviewResource::collection(Review::query()->latest()->paginate());
    }

    public function store(StoreReviewRequest $request)
    {
        $review = Review::create($request->validated());

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Review $review): ReviewResource
    {
        return new ReviewResource($review);
    }

    public function update(UpdateReviewRequest $request, Review $review): ReviewResource
    {
        $review->update($request->validated());

        return new ReviewResource($review->fresh());
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->noContent();
    }
}

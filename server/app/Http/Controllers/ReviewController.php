<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(string $media_type, int $media_id): JsonResponse
    {
        $reviews = Review::with('user')
            ->mediaType(MediaType::from($media_type))
            ->mediaId($media_id)
            ->get();

        return response()->json($reviews);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = Review::create([
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'user_id' => Auth::id(),
            'media_id' => $request->input('media_id'),
            'media_type' => $request->input('media_type'),
        ]);

        $review->load('user');

        return response()->json($review);
    }

    /**
     * @param UpdateReviewRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $review->update([
            "content" => $request->input('content'),
            "rating" => $request->input('rating'),
        ]);

        return response()->json($review);
    }

    /**
     * @param Review $review
     * @return JsonResponse
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(["message" => "正常にレビューを削除しました。"]);
    }
}

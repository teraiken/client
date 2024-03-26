<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @param StoreCommentRequest $request
     * @return JsonResponse
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $comment = Comment::create([
            'content' => $request->input('content'),
            'review_id' => $request->input('review_id'),
            'user_id' => Auth::id()
        ]);

        $comment->load('user');

        return response()->json($comment);
    }

    /**
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        if (Auth::id() !== $comment->user_id) {
            return response()->json(["message" => "権限がありません"], 401);
        }

        $comment->update([
            'content' => $request->input('content')
        ]);

        return response()->json($comment);
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(['message' => '無事に削除できました']);
    }
}

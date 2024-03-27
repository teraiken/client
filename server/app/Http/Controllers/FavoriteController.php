<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use App\Http\Requests\CheckFavoriteRequest;
use App\Http\Requests\ToggleFavoriteRequest;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $api_key = config("services.tmdb.api_key");
        $details = [];

        foreach (Auth::user()->favorites as $favorite) {
            $response = Http::get("https://api.themoviedb.org/3/{$favorite->media_type}/{$favorite->media_id}?api_key={$api_key}");

            if ($response->successful()) {
                $details[] = array_merge($response->json(), ["media_type" => $favorite->media_type]);
            }
        }

        return response()->json($details);
    }

    /**
     * @param ToggleFavoriteRequest $request
     * @return JsonResponse
     */
    public function toggleFavorite(ToggleFavoriteRequest $request): JsonResponse
    {
        $existingFavorite = Favorite::userId(Auth::id())
            ->mediaType(MediaType::from($request->input('media_type')))
            ->mediaId($request->input('media_id'))
            ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();

            return response()->json(["status" => "removed"]);
        } else {
            Favorite::create([
                'media_type' => $request->input('media_type'),
                'media_id' => $request->input('media_id'),
                'user_id' => Auth::id(),
            ]);

            return response()->json(["status" => "added"]);
        }
    }

    /**
     * @param CheckFavoriteRequest $request
     * @return JsonResponse
     */
    public function checkFavoriteStatus(CheckFavoriteRequest $request): JsonResponse
    {
        $isFavorite = Favorite::userId(Auth::id())
            ->mediaType(MediaType::from($request->input('media_type')))
            ->mediaId($request->input('media_id'))
            ->exists();

        return response()->json($isFavorite);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $this->authorize('like', $post);

        $user = Auth::user();

        $isLiked = $post->likes()->where('user_id', $user->id)->exists();

        DB::transaction(function () use ($post, $user, $isLiked) {
            if($isLiked){
                $post->likes()->detach($user->id);
            }
            else{
                $post->likes()->attach($user->id);
            }
        });

        //Актуальное состояние
        return response()->json([
            'success' => true,
            'liked' => !$isLiked,
            'likesCount' => $post->likes()->count(),
        ]);
    }
}

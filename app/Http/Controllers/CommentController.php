<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function store(Request $request, Post $post)
    {
        $this->authorize('create', Comment::class);

        $valid = $request->validate([
            'content' => 'required|string|min:' . $this->settingsService->get('comments.length.min') . 
            '|max:' . $this->settingsService->get('comments.length.max') ,
        ]);

        $comment = $post->comments()->create([
            'content' => $valid['content'],
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Комментарий добавлен');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', Comment::class);

        $valid = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        $comment->update($valid);

        return back()->with('success', 'Комментарий Обновлен');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Комментарий удален');
    }
}

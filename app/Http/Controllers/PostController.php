<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $postsQuery = Post::query()
            ->with([
                'author:id,name,image',
                'category:id,name',
                'comments' => fn ($query) => $query->latest()->with('author:id,name,image'),
            ])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(25);

        if (auth()->check()) {
            $postsQuery->with([
                'likes' => fn ($query) => $query->where('users.id', auth()->id()),
            ]);
        }

        $posts = $postsQuery->get()->map(function (Post $post): array {
            $content = trim(strip_tags((string) $post->content));
            $authorSeed = (int) ($post->author?->id ?? $post->id);
            $postSeed = (int) ($post->id + 11);

            return [
                'id' => $post->id,
                'author_photo' => $this->localImageUrl($authorSeed),
                'author_name' => $post->author?->name ?: 'Author',
                'published_at' => $post->created_at ? $post->created_at->format('d.m.Y H:i') : '-',
                'views' => (string) $post->views,
                'title' => $post->title,
                'excerpt' => Str::limit($content, 220),
                'category_name' => $post->category?->name ?: 'Без категории',
                'description' => $content,
                'image' => $this->localImageUrl($postSeed),
                'content' => $content,
                'likes_count' => (int) $post->likes_count,
                'comments_count' => (int) $post->comments_count,
                'liked' => auth()->check() ? $post->likes->isNotEmpty() : false,
                'like_url' => route('posts.like', ['post' => $post]),
                'comment_url' => route('comments.store', ['post' => $post]),
                'comments' => $post->comments->map(static function ($comment): array {
                    return [
                        'id' => $comment->id,
                        'author_name' => $comment->author?->name ?: 'Author',
                        'created_at' => $comment->created_at ? $comment->created_at->format('d.m.Y H:i') : '-',
                        'content' => $comment->content,
                    ];
                })->values()->all(),
                'links' => [],
            ];
        })->values()->all();

        $posts = $this->buildLinks($posts);
        $postCount = count($posts);
        $start = 0;

        if ($postCount > 0) {
            $oldPostId = (int) old('post_id', 0);
            $oldIndex = array_search($oldPostId, array_column($posts, 'id'), true);
            $start = $oldIndex === false ? random_int(0, $postCount - 1) : $oldIndex;
        }

        return view('welcome', [
            'posts' => $posts,
            'postCount' => $postCount,
            'start' => $start,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        return view('posts.create', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request, SettingsService $settingsService): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'content' => [
                'required',
                'string',
                'min:' . (int) $settingsService->get('posts.length.min', 1),
                'max:' . (int) $settingsService->get('posts.length.max', 10000),
            ],
            'image' => ['nullable', 'string', 'max:2048'],
            'published' => ['nullable', 'boolean'],
        ]);

        $slugBase = Str::slug($validated['title']);

        if ($slugBase === '') {
            $slugBase = 'post';
        }

        $slug = $slugBase;
        $counter = 2;

        while (Post::withoutGlobalScopes()->withTrashed()->where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        Post::query()->create([
            'user_id' => Auth::id(),
            'category_id' => (int) $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'image' => trim((string) ($validated['image'] ?? '')),
            'published' => (bool) ($validated['published'] ?? false),
            'views' => 0,
        ]);

        return redirect()->route('home')->with('success', 'Пост создан');
    }

    private function buildLinks(array $posts): array
    {
        $postCount = count($posts);
        $directions = [
            'upLeft' => [-1, -1],
            'up' => [-1, 0],
            'upRight' => [-1, 1],
            'left' => [0, -1],
            'right' => [0, 1],
            'downLeft' => [1, -1],
            'down' => [1, 0],
            'downRight' => [1, 1],
        ];

        foreach ($posts as $index => &$post) {
            $row = intdiv($index, 5);
            $col = $index % 5;

            foreach ($directions as $direction => [$dRow, $dCol]) {
                $nextRow = $row + $dRow;
                $nextCol = $col + $dCol;

                if ($nextRow < 0 || $nextRow >= 5 || $nextCol < 0 || $nextCol >= 5) {
                    $post['links'][$direction] = null;
                    continue;
                }

                $nextIndex = ($nextRow * 5) + $nextCol;

                if ($nextIndex >= $postCount) {
                    $post['links'][$direction] = null;
                    continue;
                }

                $post['links'][$direction] = $posts[$nextIndex]['id'];
            }
        }
        unset($post);

        return $posts;
    }

    private function localImageUrl(int $seed): string
    {
        $index = (($seed - 1) % 30) + 1;
        $relativePath = 'random-images/' . $index . '.png';
        $absolutePath = public_path($relativePath);
        $version = file_exists($absolutePath) ? (string) filemtime($absolutePath) : (string) time();

        return asset($relativePath) . '?v=' . $version;
    }
}
